<?php

namespace App\Actions\Payables;

use App\Models\AccountPayable;
use App\Models\PayablePayment;
use App\Enums\PayableStatus;
use App\Enums\PurchaseStatus;
use App\Context\CompanyContext;
use Illuminate\Support\Facades\DB;
use Exception;

class RegisterPayablePayment
{
    public function execute(int $payableId, float $amount, ?string $paymentDate = null, ?string $reference = null, ?string $notes = null, ?int $financialAccountId = null): PayablePayment
    {
        return DB::transaction(function () use ($payableId, $amount, $paymentDate, $reference, $notes, $financialAccountId) {
            $company = app(CompanyContext::class)->getCompany();

            // lockForUpdate to prevent concurrent double payment.
            $payable = AccountPayable::where('id', $payableId)
                ->where('company_id', $company->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($payable->status === PayableStatus::CANCELLED) {
                throw new Exception("No se puede abonar a una CxP anulada.");
            }

            if ($amount <= 0) {
                throw new Exception("El abono debe ser mayor a cero.");
            }

            if ($amount > $payable->balance) {
                throw new Exception("El abono no puede superar el saldo actual de la CxP.");
            }

            $payment = PayablePayment::create([
                'company_id' => $company->id,
                'account_payable_id' => $payable->id,
                'amount' => $amount,
                'payment_date' => $paymentDate ?? now()->toDateString(),
                'reference' => $reference,
                'notes' => $notes,
                'created_by' => auth()->id() ?? 1, // fallback for testing
            ]);

            $payable->paid_amount += $amount;
            $payable->balance -= $amount;

            if ($payable->balance <= 0.001) { // float precision handling just in case
                $payable->status = PayableStatus::PAID;
                $payable->balance = 0;
            } else {
                $payable->status = PayableStatus::PARTIAL;
            }

            $payable->save();

            // Update Purchase status
            $purchase = $payable->purchase;
            if ($purchase) {
                $purchase->paid_amount += $amount;
                $purchase->balance -= $amount;
                
                if ($payable->status === PayableStatus::PAID) {
                    $purchase->status = PurchaseStatus::PAID;
                    $purchase->balance = 0;
                } else {
                    $purchase->status = PurchaseStatus::PARTIALLY_PAID;
                }
                $purchase->save();
            }

            // Integración opcional con Treasury
            $moduleManager = app(\App\Services\ModuleManager::class);
            if ($moduleManager->isActive($company, 'treasury')) {
                if (!$financialAccountId) {
                    throw new Exception("El módulo de tesorería está activo, debe seleccionar una cuenta financiera.");
                }

                app(\App\Services\Treasury\TreasuryService::class)->recordMovement(
                    $company->id,
                    $financialAccountId,
                    \App\Enums\FinancialMovementType::PAYABLE_PAYMENT,
                    $amount,
                    "Pago CxP a Compra #{$payable->purchase->number}",
                    $paymentDate ?? now()->toDateTimeString(),
                    $payment,
                    $notes
                );
            }

            return $payment;
        });
    }
}
