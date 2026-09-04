# Contrato Frontend: CORE & MULTIEMPRESA

Este documento establece el contrato entre Backend y Frontend para las funcionalidades centrales (Multitenancy, Configuración de Empresa, Usuarios y Módulos).

## 1. Módulo: Configuración de Empresa (Settings)

### Componente Livewire: `App\Livewire\Settings\Company\EditCompany`
- **Ubicación Vista:** `resources/views/settings/company/edit.blade.php` o similar.
- **Propiedades Públicas:**
  - `string $name`
  - `string $country_code`
  - `string $currency_code`
  - `string $timezone`
- **Métodos:**
  - `save()`: Guarda los cambios. Emite mensaje flash o evento `company-updated`.

### Componente Livewire: `App\Livewire\Settings\Modules\ModuleManager`
- **Ubicación Vista:** `resources/views/settings/modules/index.blade.php`
- **Propiedades Públicas:**
  - `Collection $availableModules`: Todos los módulos del sistema (Model `App\Models\Module`).
  - `array $activeModuleKeys`: Array simple con las llaves (strings) de los módulos activos.
- **Métodos:**
  - `toggleModule(string $moduleKey)`: Activa o desactiva el módulo.
- **Excepciones / Errores Esperables:**
  - Mensajes de error si se intenta activar un módulo sin sus dependencias.
  - Mensajes de error si se intenta desactivar un módulo que es dependencia de otro activo.

### Componente Livewire: `App\Livewire\Settings\Users\ManageUsers`
- **Ubicación Vista:** `resources/views/settings/users/index.blade.php`
- **Propiedades Públicas:**
  - `Collection $users`: Usuarios de la empresa actual (con acceso a su pivot `role_id`).
  - `Collection $availableRoles`: Roles disponibles (`owner`, `admin`, `manager`, `operator`, `viewer`).
- **Métodos:**
  - `inviteUser(string $email, string $roleKey)`: Envía invitación.
  - `changeRole(int $userId, string $roleKey)`: Modifica el rol.
  - `removeUser(int $userId)`: Expulsa de la empresa.

## 2. Permisos y Autorización (Blade)

Para ocultar/mostrar elementos UI en Blade, utilizar las directivas o la fachada provista (el usuario debe estar autenticado):
```blade
@if(auth()->user()->hasPermission('inventory.view', app(\App\Context\CompanyContext::class)->currentCompany()))
    <!-- UI Element -->
@endif
```
*(Nota: Eventualmente implementaremos una directiva personalizada `@companycan` para simplificar esto).*

## 3. Estados de Membresía
Un usuario puede estar en la empresa como:
- `active`: Trabaja normalmente.
- `invited`: Aún no acepta la invitación.
- `suspended`: Bloqueado por el administrador.
