<?php

namespace App\Domain\Payment\Repositories;

use App\Domain\Payment\Models\ManualPaymentMethod;
use App\Domain\Payment\Models\Payment;
use App\Domain\Payment\Models\PaymentGateway;
use App\Domain\Payment\Models\PaymentListenerDevice;
use App\Domain\Payment\Models\PaymentListenerPayment;
use App\Domain\Payment\Models\SubscriptionPayment;
use App\Domain\Payment\Repositories\Contracts\PaymentRepositoryInterface;
use Illuminate\Support\Collection;

class EloquentPaymentRepository implements PaymentRepositoryInterface
{
    public function findById(int $id): ?Payment
    {
        return Payment::find($id);
    }

    public function findByTransactionId(string $transactionId): ?Payment
    {
        return Payment::where('transaction_id', $transactionId)->first();
    }

    public function create(array $data): Payment
    {
        return Payment::create($data);
    }

    public function update(Payment $payment, array $data): bool
    {
        return $payment->update($data);
    }

    public function delete(int $id): bool
    {
        return Payment::destroy($id) > 0;
    }

    public function getByUser(int $userId): Collection
    {
        return Payment::where('user_id', $userId)->get();
    }

    public function getEnabledGateways(): Collection
    {
        return PaymentGateway::where('is_enabled', true)->get();
    }

    public function getDefaultGateway(): ?PaymentGateway
    {
        return PaymentGateway::where('is_default', true)->first();
    }

    public function findGatewayById(int $id): ?PaymentGateway
    {
        return PaymentGateway::find($id);
    }

    public function getEnabledManualMethods(): Collection
    {
        return ManualPaymentMethod::where('is_active', true)->get();
    }

    public function findListenerDeviceBySeller(int $sellerId): ?PaymentListenerDevice
    {
        return PaymentListenerDevice::where('seller_id', $sellerId)->first();
    }

    public function findListenerDeviceByPhone(string $phone): ?PaymentListenerDevice
    {
        return PaymentListenerDevice::where('phone', $phone)->first();
    }

    public function createListenerPayment(array $data): PaymentListenerPayment
    {
        return PaymentListenerPayment::create($data);
    }

    public function getListenerPayments(int $deviceId): Collection
    {
        return PaymentListenerPayment::where('device_id', $deviceId)->get();
    }

    public function createSubscriptionPayment(array $data): SubscriptionPayment
    {
        return SubscriptionPayment::create($data);
    }
}
