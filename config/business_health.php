<?php

return [
    'thresholds' => [
        'receivables' => [
            'overdue_percentage_attention' => 15.0, // 15%
            'overdue_percentage_critical' => 30.0, // 30%
        ],
        'payables' => [
            'overdue_percentage_attention' => 10.0,
            'overdue_percentage_critical' => 25.0,
        ],
        'inventory' => [
            'low_stock_attention_count' => 1, // Any low stock is attention
            'out_of_stock_critical_count' => 1, // Any out of stock is critical
        ],
        'cash_flow' => [
            // If negative, it's critical. We don't need a numeric threshold for that, just < 0.
        ]
    ],
    'competencies' => [
        'cash_flow_management' => 'Gestión de flujo de caja',
        'accounts_receivable_management' => 'Gestión de cuentas por cobrar',
        'accounts_payable_management' => 'Gestión de cuentas por pagar',
        'inventory_management' => 'Gestión de inventario',
        'sales_management' => 'Gestión de ventas',
        'purchase_management' => 'Gestión de compras',
        'budgeting' => 'Elaboración de presupuestos',
        'financial_planning' => 'Planificación financiera',
    ],
];
