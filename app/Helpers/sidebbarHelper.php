<?php
use App\Models\CmsModule;
use App\Models\CmsModulePermission;

use Illuminate\Support\Facades\Auth;


function dynamic_sidebar()
{
    
    $roleId = auth()->user()?->roles->first()?->id;
    
    if (! $roleId) {
        return collect();
    }
    
    return CmsModule::with(['children' => function ($q) use ($roleId) {
            $q->whereHas('permissions', function ($perm) use ($roleId) {
                $perm->where('role_id', $roleId)
                     ->where('is_view', 1);
            });
        }])
        ->where('parent_id', 0)
        ->where('status', 'active')
        ->whereHas('permissions', function ($q) use ($roleId) {
            $q->where('role_id', $roleId)
              ->where('is_view', 1);
        })
        ->orderBy('sort_order')
        ->get();
    
}
