import os

base = r'c:\Users\Mamba\Desktop\Chanchito Nica'
lw_dir = os.path.join(base, 'app', 'Livewire')
view_dir = os.path.join(base, 'resources', 'views', 'livewire')

classes = {
    'Treasury/Dashboard.php': '''<?php
namespace App\\Livewire\\Treasury;
use Livewire\\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.treasury.dashboard')->layout('layouts.app');
    }
}
''',
    'Treasury/AccountList.php': '''<?php
namespace App\\Livewire\\Treasury;
use Livewire\\Component;

class AccountList extends Component
{
    public function render()
    {
        return view('livewire.treasury.account-list')->layout('layouts.app');
    }
}
''',
    'Treasury/MovementList.php': '''<?php
namespace App\\Livewire\\Treasury;
use Livewire\\Component;

class MovementList extends Component
{
    public function render()
    {
        return view('livewire.treasury.movement-list')->layout('layouts.app');
    }
}
''',
    'Catalog/ProductList.php': '''<?php
namespace App\\Livewire\\Catalog;
use Livewire\\Component;

class ProductList extends Component
{
    public $products = [];
    public $search = '';
    
    public function deactivate($productId) {}
    
    public function render()
    {
        return view('livewire.catalog.product-list')->layout('layouts.app');
    }
}
''',
    'Catalog/ProductForm.php': '''<?php
namespace App\\Livewire\\Catalog;
use Livewire\\Component;

class ProductForm extends Component
{
    public $productId;
    public $category_id;
    public $unit_id;
    public $sku;
    public $name;
    public $description;
    public $type;
    public $sale_price;
    public $cost;
    public $track_inventory;
    
    public $categories = [];
    public $units = [];
    
    public function save() {}
    
    public function render()
    {
        return view('livewire.catalog.product-form')->layout('layouts.app');
    }
}
''',
    'Catalog/CategoryList.php': '''<?php
namespace App\\Livewire\\Catalog;
use Livewire\\Component;

class CategoryList extends Component
{
    public function render()
    {
        return view('livewire.catalog.category-list')->layout('layouts.app');
    }
}
''',
    'Catalog/UnitList.php': '''<?php
namespace App\\Livewire\\Catalog;
use Livewire\\Component;

class UnitList extends Component
{
    public function render()
    {
        return view('livewire.catalog.unit-list')->layout('layouts.app');
    }
}
''',
    'Customers/CustomerList.php': '''<?php
namespace App\\Livewire\\Customers;
use Livewire\\Component;

class CustomerList extends Component
{
    public $customers = [];
    public $search = '';
    
    public function deactivate($customerId) {}
    
    public function render()
    {
        return view('livewire.customers.customer-list')->layout('layouts.app');
    }
}
''',
    'Customers/CustomerForm.php': '''<?php
namespace App\\Livewire\\Customers;
use Livewire\\Component;

class CustomerForm extends Component
{
    public $customerId;
    public $type;
    public $name;
    public $legal_name;
    public $identification;
    public $email;
    public $phone;
    public $address;
    public $credit_limit;
    public $credit_days;
    public $notes;
    
    public function save() {}
    
    public function render()
    {
        return view('livewire.customers.customer-form')->layout('layouts.app');
    }
}
''',
    'Suppliers/SupplierList.php': '''<?php
namespace App\\Livewire\\Suppliers;
use Livewire\\Component;

class SupplierList extends Component
{
    public $suppliers = [];
    public $search = '';
    
    public function deactivate($supplierId) {}
    
    public function render()
    {
        return view('livewire.suppliers.supplier-list')->layout('layouts.app');
    }
}
''',
    'Suppliers/SupplierForm.php': '''<?php
namespace App\\Livewire\\Suppliers;
use Livewire\\Component;

class SupplierForm extends Component
{
    public $supplierId;
    public $type;
    public $name;
    public $legal_name;
    public $identification;
    public $email;
    public $phone;
    public $address;
    public $notes;
    public $payment_terms_days = 0;
    
    public function save() {}
    
    public function render()
    {
        return view('livewire.suppliers.supplier-form')->layout('layouts.app');
    }
}
'''
}

views = {
    'treasury/dashboard.blade.php': '<div class="p-6 bg-white rounded-2xl shadow-sm text-brand-navy-900"><h1 class="text-2xl font-bold font-gliker text-brand-green-700">Treasury Dashboard</h1></div>',
    'treasury/account-list.blade.php': '<div class="p-6 bg-white rounded-2xl shadow-sm text-brand-navy-900"><h1 class="text-2xl font-bold font-gliker text-brand-green-700">Accounts</h1></div>',
    'treasury/movement-list.blade.php': '<div class="p-6 bg-white rounded-2xl shadow-sm text-brand-navy-900"><h1 class="text-2xl font-bold font-gliker text-brand-green-700">Movements</h1></div>',
    
    'catalog/product-list.blade.php': '<div class="p-6 bg-white rounded-2xl shadow-sm text-brand-navy-900"><h1 class="text-2xl font-bold font-gliker text-brand-green-700">Products</h1></div>',
    'catalog/product-form.blade.php': '<div class="p-6 bg-white rounded-2xl shadow-sm text-brand-navy-900"><h1 class="text-2xl font-bold font-gliker text-brand-green-700">Product Form</h1></div>',
    'catalog/category-list.blade.php': '<div class="p-6 bg-white rounded-2xl shadow-sm text-brand-navy-900"><h1 class="text-2xl font-bold font-gliker text-brand-green-700">Categories</h1></div>',
    'catalog/unit-list.blade.php': '<div class="p-6 bg-white rounded-2xl shadow-sm text-brand-navy-900"><h1 class="text-2xl font-bold font-gliker text-brand-green-700">Units</h1></div>',
    
    'customers/customer-list.blade.php': '<div class="p-6 bg-white rounded-2xl shadow-sm text-brand-navy-900"><h1 class="text-2xl font-bold font-gliker text-brand-green-700">Customers</h1></div>',
    'customers/customer-form.blade.php': '<div class="p-6 bg-white rounded-2xl shadow-sm text-brand-navy-900"><h1 class="text-2xl font-bold font-gliker text-brand-green-700">Customer Form</h1></div>',
    
    'suppliers/supplier-list.blade.php': '<div class="p-6 bg-white rounded-2xl shadow-sm text-brand-navy-900"><h1 class="text-2xl font-bold font-gliker text-brand-green-700">Suppliers</h1></div>',
    'suppliers/supplier-form.blade.php': '<div class="p-6 bg-white rounded-2xl shadow-sm text-brand-navy-900"><h1 class="text-2xl font-bold font-gliker text-brand-green-700">Supplier Form</h1></div>',
}

for k, v in classes.items():
    p = os.path.join(lw_dir, k)
    os.makedirs(os.path.dirname(p), exist_ok=True)
    with open(p, 'w') as f:
        f.write(v)

for k, v in views.items():
    p = os.path.join(view_dir, k)
    os.makedirs(os.path.dirname(p), exist_ok=True)
    with open(p, 'w') as f:
        f.write(v)

print('Success')
