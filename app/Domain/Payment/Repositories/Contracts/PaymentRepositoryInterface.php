<?php

namespace App\Domain\Payment\Repositories\Contracts;

use App\Domain\Payment\Models\ManualPaymentMethod;
use App\Domain\Payment\Models\Payment;
use App\Domain\Payment\Models\PaymentGateway;
use App\Domain\Payment\Models\PaymentListenerDevice;
use App\Domain\Payment\Models\PaymentListenerPayment;
use App\Domain\Payment\Models\SubscriptionPayment;
use Illuminate\Support\Collection;

interface PaymentRepositoryInterface
{
    public function findById(int $id): ?Payment;

    public function findByTransactionId(string $transactionId): ?Payment;

    public function create(array $data): Payment;

    public function update(Payment $payment, array $data): bool;

    public function delete(int $id): bool;

    public function getByUser(int $userId): Collection;

    public function getEnabledGateways(): Collection;

    public function getDefaultGateway(): ?PaymentGateway;

    public function findGatewayById(int $id): ?PaymentGateway;

    public function getEnabledManualMethods(): Collection;

    public function findListenerDeviceBySeller(int $sellerId): ?PaymentListenerDevice;

    public function getListenerDevicesBySeller(int $sellerId): Collection;

    public function findListenerDeviceByPhone(string $phone): ?PaymentListenerDevice;

    public function findListenerDeviceByCode(string $code): ?PaymentListenerDevice;

    public function createListenerPayment(array $data): PaymentListenerPayment;

    public function getListenerPayments(int $deviceId): Collection;

    public function getListenerPaymentsBySeller(int $sellerId): Collection;

    public function createSubscriptionPayment(array $data): SubscriptionPayment;
}
