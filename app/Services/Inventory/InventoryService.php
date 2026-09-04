<?php

namespace App\Services\Inventory;

use App\Models\Product;
use App\Models\Warehouse;
use App\Models\InventoryStock;
use App\Models\InventoryMovement;
use App\Enums\MovementType;
use App\Context\CompanyContext;
use Illuminate\Support\Facades\DB;
use Exception;

class InventoryService
{
    public function setOpeningStock(Warehouse $warehouse, Product $product, float $quantity, ?string $notes = null): InventoryMovement
    {
        return $this->processMovement($warehouse, $product, MovementType::OPENING, abs($quantity), $notes, 'Configuración inicial de stock');
    }

    public function registerEntry(Warehouse $warehouse, Product $product, float $quantity, ?string $referenceType = null, ?int $referenceId = null, ?string $notes = null): InventoryMovement
    {
        return $this->processMovement($warehouse, $product, MovementType::IN, abs($quantity), $notes, 'Entrada de inventario', $referenceType, $referenceId);
    }

    public function registerExit(Warehouse $warehouse, Product $product, float $quantity, ?string $referenceType = null, ?int $referenceId = null, ?string $notes = null): InventoryMovement
    {
        return $this->processMovement($warehouse, $product, MovementType::OUT, abs($quantity), $notes, 'Salida de inventario', $referenceType, $referenceId);
    }

    public function adjustStock(Warehouse $warehouse, Product $product, float $realQuantity, string $reason, ?string $notes = null): InventoryMovement
    {
        $this->validateProductAndWarehouse($product, $warehouse);

        return DB::transaction(function () use ($warehouse, $product, $realQuantity, $reason, $notes) {
            $stock = InventoryStock::where('warehouse_id', $warehouse->id)
                ->where('product_id', $product->id)
                ->lockForUpdate()
                ->first();

            $currentQuantity = $stock ? $stock->quantity : 0;
            $difference = $realQuantity - $currentQuantity;

            if ($difference == 0) {
                throw new Exception("La cantidad real es igual a la cantidad actual en el sistema.");
            }

            $type = $difference > 0 ? MovementType::ADJUSTMENT_IN : MovementType::ADJUSTMENT_OUT;

            return $this->processMovement($warehouse, $product, $type, abs($difference), $notes, $reason);
        });
    }

    public function transferStock(Warehouse $source, Warehouse $destination, Product $product, float $quantity, ?string $notes = null): array
    {
        if ($source->company_id !== $destination->company_id) {
            throw new Exception("No se puede transferir entre diferentes empresas.");
        }
        
        if ($source->id === $destination->id) {
            throw new Exception("El almacén de origen y destino deben ser diferentes.");
        }

        return DB::transaction(function () use ($source, $destination, $product, $quantity, $notes) {
            $out = $this->processMovement($source, $product, MovementType::TRANSFER_OUT, abs($quantity), "Transferencia hacia " . $destination->name, 'Transferencia interna');
            $in = $this->processMovement($destination, $product, MovementType::TRANSFER_IN, abs($quantity), "Transferencia desde " . $source->name, 'Transferencia interna');

            return ['out' => $out, 'in' => $in];
        });
    }

    protected function processMovement(
        Warehouse $warehouse, 
        Product $product, 
        MovementType $type, 
        float $quantity, 
        ?string $notes, 
        ?string $reason,
        ?string $referenceType = null,
        ?int $referenceId = null
    ): InventoryMovement {
        if ($quantity <= 0) {
            throw new Exception("La cantidad del movimiento debe ser mayor a cero.");
        }

        $this->validateProductAndWarehouse($product, $warehouse);

        return DB::transaction(function () use ($warehouse, $product, $type, $quantity, $notes, $reason, $referenceType, $referenceId) {
            $stock = InventoryStock::firstOrCreate(
                ['warehouse_id' => $warehouse->id, 'product_id' => $product->id],
                ['company_id' => $warehouse->company_id, 'quantity' => 0]
            );

            // Re-fetch with lock to ensure strict concurrency
            $stock = InventoryStock::where('id', $stock->id)->lockForUpdate()->first();

            $previousQuantity = $stock->quantity;
            
            if ($type->isPositive()) {
                $stock->quantity += $quantity;
            } elseif ($type->isNegative()) {
                $stock->quantity -= $quantity;
                if ($stock->quantity < 0) {
                    throw new Exception("No hay suficiente stock disponible para realizar esta operación.");
                }
            }

            $stock->save();

            $movement = InventoryMovement::create([
                'company_id' => $warehouse->company_id,
                'warehouse_id' => $warehouse->id,
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'type' => $type,
                'quantity' => $quantity,
                'previous_quantity' => $previousQuantity,
                'new_quantity' => $stock->quantity,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'reason' => $reason,
                'notes' => $notes,
                'occurred_at' => now(),
            ]);

            return $movement;
        });
    }

    protected function validateProductAndWarehouse(Product $product, Warehouse $warehouse)
    {
        $companyId = app(CompanyContext::class)->getCompanyId();

        if ($warehouse->company_id !== $companyId) {
            throw new Exception("El almacén no pertenece a la empresa activa.");
        }

        if ($product->company_id !== $companyId) {
            throw new Exception("El producto no pertenece a la empresa activa.");
        }

        if (!$product->track_inventory) {
            throw new Exception("El producto seleccionado no controla inventario.");
        }
    }
}
