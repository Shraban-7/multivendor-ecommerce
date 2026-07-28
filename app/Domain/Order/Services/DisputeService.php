<?php

namespace App\Domain\Order\Services;

use App\Domain\Order\Enums\DisputeResolution;
use App\Domain\Order\Enums\DisputeStatus;
use App\Domain\Order\Enums\ReturnEventType;
use App\Domain\Order\Enums\ReturnStatus;
use App\Domain\Order\Models\Dispute;
use App\Domain\Order\Models\ReturnEvent;
use App\Domain\Order\Models\ReturnRequest;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

class DisputeService
{
    public function __construct(
        private readonly ReturnService $returnService,
        private readonly RefundService $refundService,
    ) {}

    public function openDispute(
        ReturnRequest $return,
        int $raisedBy,
        string $reason,
        ?string $description = null,
    ): Dispute {
        if (! in_array($return->status, [
            ReturnStatus::REJECTED,
            ReturnStatus::REJECTED->value,
        ], true)) {
            throw new RuntimeException('Disputes can only be opened on rejected returns.');
        }

        if ($return->is_disputed && $return->dispute) {
            throw new RuntimeException('A dispute already exists for this return.');
        }

        return DB::transaction(function () use ($return, $raisedBy, $reason, $description) {
            $return->update(['is_disputed' => true]);

            $dispute = $return->dispute()->create([
                'raised_by' => $raisedBy,
                'reason' => $reason,
                'description' => $description,
                'status' => DisputeStatus::OPEN->value,
            ]);

            ReturnEvent::log(
                $return,
                ReturnEventType::DISPUTE_OPENED->value,
                'customer',
                $raisedBy,
                null,
                null,
                'Customer opened dispute: '.$reason,
            );

            notify_admin(
                'Dispute Raised',
                "A dispute was opened for return {$return->rma_number}.",
                'dispute',
                $dispute->id,
            );
            notify_seller(
                $return->order->seller_id,
                'Dispute Raised',
                "Customer opened a dispute on return {$return->rma_number}.",
                'return',
                $return->id,
            );

            return $dispute;
        });
    }

    public function sellerRespond(Dispute $dispute, string $response, ?int $sellerEmployeeId = null): Dispute
    {
        if (! $dispute->isOpen()) {
            throw new RuntimeException('Dispute is closed; cannot add response.');
        }

        return DB::transaction(function () use ($dispute, $response, $sellerEmployeeId) {
            $dispute->update([
                'seller_response' => $response,
                'seller_responded_at' => now(),
                'status' => DisputeStatus::SELLER_RESPONSE->value,
            ]);

            ReturnEvent::log(
                $dispute->returnRequest,
                ReturnEventType::DISPUTE_RESPONSE->value,
                'seller',
                $sellerEmployeeId,
                null,
                null,
                'Seller responded to dispute',
            );

            return $dispute->fresh();
        });
    }

    public function resolve(
        Dispute $dispute,
        DisputeResolution $resolution,
        ?int $adminId = null,
        ?string $adminNote = null,
        ?float $amount = null,
    ): Dispute {
        if (! $dispute->isOpen()) {
            throw new RuntimeException('Dispute already closed.');
        }

        if ($resolution === DisputeResolution::PARTIAL_REFUND && ($amount === null || $amount <= 0)) {
            throw new InvalidArgumentException('Partial refund requires a positive amount.');
        }

        return DB::transaction(function () use ($dispute, $resolution, $adminId, $adminNote, $amount) {
            $return = $dispute->returnRequest;

            $dispute->update([
                'status' => DisputeStatus::RESOLVED->value,
                'resolution' => $resolution->value,
                'admin_note' => $adminNote,
                'resolution_amount' => $amount,
                'assigned_admin_id' => $adminId,
                'resolved_at' => now(),
            ]);

            if ($resolution === DisputeResolution::APPROVED) {
                $return->update([
                    'status' => ReturnStatus::APPROVED->value,
                    'approved_at' => now(),
                    'rejection_reason' => null,
                ]);
                $this->returnService->markItemReceived($return, 'admin', $adminId, 'Resolved by admin via dispute approval');
            } elseif ($resolution === DisputeResolution::REJECTED) {
                ReturnEvent::log(
                    $return,
                    ReturnEventType::DISPUTE_RESOLVED->value,
                    'admin',
                    $adminId,
                    null,
                    null,
                    'Dispute rejected by admin',
                );
            } elseif ($resolution === DisputeResolution::PARTIAL_REFUND) {
                $return->update([
                    'status' => ReturnStatus::APPROVED->value,
                    'approved_at' => now(),
                ]);
                $this->refundService->initiate($return, 'admin', $adminId);
                ReturnEvent::log(
                    $return,
                    ReturnEventType::DISPUTE_RESOLVED->value,
                    'admin',
                    $adminId,
                    null,
                    null,
                    "Dispute resolved with partial refund of {$amount}",
                );
            } elseif ($resolution === DisputeResolution::WALLET_CREDIT) {
                $return->update(['status' => ReturnStatus::APPROVED->value, 'approved_at' => now()]);
                $this->refundService->initiate($return, 'admin', $adminId);
            }

            notify_user(
                $return->user_id,
                'Dispute Resolved',
                "Your dispute for return {$return->rma_number} has been resolved.",
                'return',
                $return->id,
            );
            notify_seller(
                $return->order->seller_id,
                'Dispute Resolved',
                "Dispute on return {$return->rma_number} resolved with outcome: {$resolution->label()}",
                'return',
                $return->id,
            );

            return $dispute->fresh();
        });
    }
}
