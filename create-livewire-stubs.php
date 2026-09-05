<?php

$stubs = [
    'Dashboard\Dashboard' => 'dashboard.dashboard',
    
    'Catalog\ProductList' => 'catalog.product-list',
    'Catalog\CategoryList' => 'catalog.category-list',
    'Catalog\UnitList' => 'catalog.unit-list',
    
    'Customers\CustomerList' => 'customers.customer-list',
    
    'Suppliers\SupplierList' => 'suppliers.supplier-list',
    
    'Billing\InvoiceList' => 'billing.invoice-list',
    'Billing\InvoiceForm' => 'billing.invoice-form',
    'Billing\InvoiceDetail' => 'billing.invoice-detail',
    
    'Receivables\ReceivableDashboard' => 'receivables.receivable-dashboard',
    'Receivables\ReceivableList' => 'receivables.receivable-list',
    
    'Purchases\PurchaseList' => 'purchases.purchase-list',
    'Purchases\PurchaseForm' => 'purchases.purchase-form',
    'Purchases\PurchaseDetail' => 'purchases.purchase-detail',
    
    'Payables\PayableDashboard' => 'payables.payable-dashboard',
    'Payables\PayableList' => 'payables.payable-list',
    
    'Treasury\Dashboard' => 'treasury.dashboard',
    'Treasury\AccountList' => 'treasury.account-list',
    'Treasury\MovementList' => 'treasury.movement-list',
    
    'Education\Home' => 'education.home',
    'Education\Explore' => 'education.explore',
    'Education\MyLearning' => 'education.my-learning',
    'Education\ProgramDetail' => 'education.program-detail',
    'Education\LessonViewer' => 'education.lesson-viewer',
    'Education\AssessmentViewer' => 'education.assessment-viewer',
    
    'Gamification\Dashboard' => 'gamification.dashboard',
];

foreach ($stubs as $classPath => $viewPath) {
    $classParts = explode('\\', $classPath);
    $className = array_pop($classParts);
    $namespace = 'App\\Livewire\\' . implode('\\', $classParts);
    
    $dir = __DIR__ . '/app/Livewire/' . implode('/', $classParts);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    $classFile = $dir . '/' . $className . '.php';
    if (!file_exists($classFile)) {
        $content = "<?php\n\nnamespace {$namespace};\n\nuse Livewire\\Component;\n\nclass {$className} extends Component\n{\n    public function render()\n    {\n        return view('livewire.{$viewPath}');\n    }\n}\n";
        file_put_contents($classFile, $content);
    }
    
    $viewParts = explode('.', $viewPath);
    $viewName = array_pop($viewParts);
    $viewDir = __DIR__ . '/resources/views/livewire/' . implode('/', $viewParts);
    if (!is_dir($viewDir)) {
        mkdir($viewDir, 0755, true);
    }
    
    $viewFile = $viewDir . '/' . $viewName . '.blade.php';
    if (!file_exists($viewFile)) {
        file_put_contents($viewFile, "<div>\n    <!-- TODO: Implement {$className} -->\n    <h1 class=\"text-2xl font-bold p-4\">{$className} Placeholder</h1>\n</div>\n");
    }
}

echo "Stubs created.\n";
