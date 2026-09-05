<?php

namespace App\Services\Dashboard;

class BusinessAlertService
{
    public function generateAlerts(array $dashboardData): array
    {
        $alerts = [];
        $domainStatuses = [
            'sales' => 'healthy',
            'purchases' => 'healthy',
            'receivables' => 'unavailable',
            'payables' => 'unavailable',
            'treasury' => 'unavailable',
            'inventory' => 'unavailable',
        ];

        // 1. Receivables Alerts
        if (isset($dashboardData['receivables']) && $dashboardData['receivables'] !== 'unavailable') {
            $domainStatuses['receivables'] = 'healthy';
            $overduePct = $dashboardData['receivables']['overdue_percentage'] ?? 0;
            
            $attentionThreshold = config('business_health.thresholds.receivables.overdue_percentage_attention', 15);
            $criticalThreshold = config('business_health.thresholds.receivables.overdue_percentage_critical', 30);

            if ($overduePct >= $criticalThreshold) {
                $domainStatuses['receivables'] = 'critical';
                $alerts[] = $this->createAlert(
                    'high_overdue_receivables',
                    'receivables',
                    'critical',
                    number_format($overduePct, 2) . '%',
                    $criticalThreshold,
                    'Alto porcentaje de cartera vencida.',
                    'accounts_receivable_management'
                );
            } elseif ($overduePct >= $attentionThreshold) {
                $domainStatuses['receivables'] = 'attention';
                $alerts[] = $this->createAlert(
                    'moderate_overdue_receivables',
                    'receivables',
                    'attention',
                    number_format($overduePct, 2) . '%',
                    $attentionThreshold,
                    'Porcentaje de cartera vencida requiere atención.',
                    'accounts_receivable_management'
                );
            }
        }

        // 2. Payables Alerts
        if (isset($dashboardData['payables']) && $dashboardData['payables'] !== 'unavailable') {
            $domainStatuses['payables'] = 'healthy';
            $overduePct = $dashboardData['payables']['overdue_percentage'] ?? 0;
            
            $attentionThreshold = config('business_health.thresholds.payables.overdue_percentage_attention', 10);
            $criticalThreshold = config('business_health.thresholds.payables.overdue_percentage_critical', 25);

            if ($overduePct >= $criticalThreshold) {
                $domainStatuses['payables'] = 'critical';
                $alerts[] = $this->createAlert(
                    'high_overdue_payables',
                    'payables',
                    'critical',
                    number_format($overduePct, 2) . '%',
                    $criticalThreshold,
                    'Obligaciones vencidas elevadas.',
                    'accounts_payable_management'
                );
            } elseif ($overduePct >= $attentionThreshold) {
                $domainStatuses['payables'] = 'attention';
                $alerts[] = $this->createAlert(
                    'moderate_overdue_payables',
                    'payables',
                    'attention',
                    number_format($overduePct, 2) . '%',
                    $attentionThreshold,
                    'Porcentaje de cuentas por pagar vencidas en advertencia.',
                    'accounts_payable_management'
                );
            }
        }

        // 3. Inventory Alerts
        if (isset($dashboardData['inventory']) && $dashboardData['inventory'] !== 'unavailable') {
            $domainStatuses['inventory'] = 'healthy';
            $outOfStock = $dashboardData['inventory']['products_out_of_stock'] ?? 0;
            $lowStock = $dashboardData['inventory']['products_low_stock'] ?? 0;

            if ($outOfStock > 0) {
                $domainStatuses['inventory'] = 'critical';
                $alerts[] = $this->createAlert(
                    'inventory_out_of_stock',
                    'inventory',
                    'critical',
                    (string) $outOfStock,
                    null,
                    'Existen productos sin existencias.',
                    'inventory_management'
                );
            } elseif ($lowStock > 0) {
                if ($domainStatuses['inventory'] !== 'critical') {
                    $domainStatuses['inventory'] = 'attention';
                }
                $alerts[] = $this->createAlert(
                    'inventory_low_stock',
                    'inventory',
                    'attention',
                    (string) $lowStock,
                    null,
                    'Existen productos con nivel bajo de existencias.',
                    'inventory_management'
                );
            }
        }

        // 4. Treasury / Cash Flow Alerts
        if (isset($dashboardData['treasury']) && $dashboardData['treasury'] !== 'unavailable') {
            $domainStatuses['treasury'] = 'healthy';
            $netFlow = $dashboardData['treasury']['net_flow'] ?? 0;

            if ($netFlow < 0) {
                $domainStatuses['treasury'] = 'critical';
                $alerts[] = $this->createAlert(
                    'negative_operational_cash_flow',
                    'treasury',
                    'critical',
                    (string) $netFlow,
                    0,
                    'El flujo de caja operativo del período es negativo.',
                    'cash_flow_management'
                );
            }
        }

        return [
            'alerts' => $alerts,
            'domain_statuses' => $domainStatuses
        ];
    }

    protected function createAlert(
        string $key, 
        string $domain, 
        string $severity, 
        string $value, 
        $threshold, 
        string $context, 
        ?string $learningTopic
    ): array {
        return [
            'key' => $key,
            'domain' => $domain,
            'severity' => $severity,
            'value' => $value,
            'threshold' => $threshold,
            'context' => $context,
            'recommended_learning_topic' => $learningTopic,
        ];
    }
}
