<?php

namespace App\Actions\Receivables;

use App\Models\AccountReceivable;
use App\Models\ReceivablePayment;
use App\Enums\ReceivableStatus;
use App\Enums\InvoiceStatus;
use App\Context\CompanyContext;
use Illuminate\Support\Facades\DB;
use Exception;

class RegisterReceivablePayment
{
    public function execute(AccountReceivable $receivable, float $amount, string $paymentDate, ?string $reference = null, ?string $notes = null, ?int $financialAccountId = null): ReceivablePayment
    {
        return DB::transaction(function () use ($receivable, $amount, $paymentDate, $reference, $notes, $financialAccountId) {
            $companyId = app(CompanyContext::class)->getCompanyId();

            if ($receivable->company_id !== $companyId) {
                throw new Exception("La cuenta por cobrar no pertenece a la empresa activa.");
            }

            if ($amount <= 0) {
                throw new Exception("El monto del abono debe ser mayor a cero.");
            }

            // Lock the row for update to prevent concurrent payments bypassing balance limits
            $receivable = AccountReceivable::where('id', $receivable->id)->lockForUpdate()->first();

            if ($receivable->status === ReceivableStatus::CANCELLED) {
                throw new Exception("La cuenta por cobrar está anulada.");
            }

            if ($amount > $receivable->balance) {
                throw new Exception("El abono no puede superar el saldo actual de la cuenta (C$ {$receivable->balance}).");
            }

            $payment = ReceivablePayment::create([
                'company_id' => $companyId,
                'account_receivable_id' => $receivable->id,
                'amount' => $amount,
                'payment_date' => $paymentDate,
                'reference' => $reference,
                'notes' => $notes,
                'created_by' => auth()->id(),
            ]);

            // Update Receivable
            $receivable->paid_amount += $amount;
            $receivable->balance -= $amount;
            
            if ($receivable->balance <= 0) {
                $receivable->status = ReceivableStatus::PAID;
            } else {
                $receivable->status = ReceivableStatus::PARTIAL;
            }
            $receivable->save();

            // Update associated Invoice
            if ($receivable->invoice) {
                $invoice = $receivable->invoice;
                $invoice->paid_amount = $receivable->paid_amount;
                $invoice->balance = $receivable->balance;
                
                if ($invoice->balance <= 0) {
                    $invoice->status = InvoiceStatus::PAID;
                } else {
                    $invoice->status = InvoiceStatus::PARTIALLY_PAID;
                }
                $invoice->save();
            }

            // Integración opcional con Treasury
            $moduleManager = app(\App\Services\ModuleManager::class);
            if ($moduleManager->isActive(app(\App\Models\Company::class)->find($companyId), 'treasury')) {
                if (!$financialAccountId) {
                    throw new Exception("El módulo de tesorería está activo, debe seleccionar una cuenta financiera.");
                }

                app(\App\Services\Treasury\TreasuryService::class)->recordMovement(
                    $companyId,
                    $financialAccountId,
                    \App\Enums\FinancialMovementType::RECEIVABLE_PAYMENT,
                    $amount,
                    "Abono CxC a Factura #{$receivable->invoice->number}",
                    $paymentDate,
                    $payment,
                    $notes
                );
            }

            return $payment;
        });
    }
}
