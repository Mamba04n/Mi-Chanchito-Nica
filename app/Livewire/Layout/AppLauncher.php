<?php

namespace App\Livewire\Layout;

use App\Services\ModuleManager;
use Livewire\Component;

class AppLauncher extends Component
{
    public array $modules = [];
    public bool $show = false;

    protected $listeners = ['toggleAppLauncher' => 'toggle'];

    public function mount(ModuleManager $moduleManager)
    {
        $company = app(\App\Context\CompanyContext::class)->getCompany();
        if (!$company) {
            $this->modules = [];
            return;
        }

        $activeKeys = $company->modules()->whereNull('company_modules.disabled_at')->pluck('key')->toArray();

        $allApps = [
            'catalog' => ['name' => 'Catálogo', 'route' => 'catalog.products', 'desc' => 'Productos y servicios', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>'],
            'inventory' => ['name' => 'Inventario', 'route' => 'inventory.index', 'desc' => 'Control de existencias', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>'],
            'customers' => ['name' => 'Clientes', 'route' => 'customers.index', 'desc' => 'Cartera de clientes', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>'],
            'suppliers' => ['name' => 'Proveedores', 'route' => 'suppliers.index', 'desc' => 'Contactos de compras', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>'],
            'billing' => ['name' => 'Facturación', 'route' => 'billing.invoices.index', 'desc' => 'Emisión de facturas', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>'],
            'receivables' => ['name' => 'CxC', 'route' => 'receivables.index', 'desc' => 'Cuentas por cobrar', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'],
            'purchases' => ['name' => 'Compras', 'route' => 'purchases.index', 'desc' => 'Órdenes de compra', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>'],
            'payables' => ['name' => 'CxP', 'route' => 'payables.index', 'desc' => 'Cuentas por pagar', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path>'],
            'treasury' => ['name' => 'Tesorería', 'route' => 'treasury.index', 'desc' => 'Control de caja y bancos', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>'],
            'reports' => ['name' => 'Reportes', 'route' => 'dashboard', 'desc' => 'Métricas del negocio', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>'],
            'education' => ['name' => 'Academia', 'route' => 'education.index', 'desc' => 'Aprende a gestionar', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>'],
            'gamification' => ['name' => 'Progreso', 'route' => 'gamification.index', 'desc' => 'Retos y logros', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>'],
        ];

        foreach ($allApps as $key => $app) {
            if (in_array($key, $activeKeys)) {
                $this->modules[] = $app;
            }
        }
    }

    public function toggle()
    {
        $this->show = !$this->show;
    }

    public function render()
    {
        return view('livewire.layout.app-launcher');
    }
}
