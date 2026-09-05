<?php

namespace App\Livewire\Settings\Users;

use Livewire\Component;
use App\Context\CompanyContext;
use App\Models\Role;
use App\Models\User;
use App\Services\AuditLogger;

class ManageUsers extends Component
{
    public $users;
    public $availableRoles;

    public function mount(CompanyContext $companyContext)
    {
        $this->availableRoles = Role::all();
        $this->loadUsers($companyContext);
    }

    public function loadUsers(CompanyContext $companyContext)
    {
        $this->users = $companyContext->getCompany()->users()->with('companies')->get();
    }

    public function changeRole($userId, $roleKey, CompanyContext $companyContext)
    {
        $company = $companyContext->getCompany();
        $role = Role::where('key', $roleKey)->firstOrFail();
        
        $company->users()->updateExistingPivot($userId, [
            'role_id' => $role->id,
        ]);

        AuditLogger::log('role.changed', $company->id, User::class, $userId, ['new_role' => $roleKey]);
        $this->loadUsers($companyContext);
        session()->flash('message', 'Rol actualizado.');
    }

    public function render()
    {
        return view('settings.users.index');
    }
}
