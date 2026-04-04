<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CmsModule extends Model
{
    protected $table = 'cms_module';

    protected $guarded = ['id'];

    public function children()
    {
        return $this->hasMany(CmsModule::class, 'parent_id', 'id')
            ->orderBy('sort_order', 'asc');
    }

    public function permissions()
    {
        return $this->hasMany(CmsModulePermission::class, 'module_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(CmsModule::class, 'parent_id', 'id');
    }



    public static function getModulesaaa($role_id = 0)
    {
        $query = self::select('cms_module.*', 'cmp.is_add', 'cmp.is_view', 'cmp.is_update', 'cmp.is_delete')
            ->with([
                'child' => function ($query) use ($role_id) {
                    $query->select('cms_module.*', 'cmp.is_add', 'cmp.is_view', 'cmp.is_update', 'cmp.is_delete')
                        ->leftJoin('cms_module_permission AS cmp', function ($leftJoin) use ($role_id) {
                            $leftJoin->on('cmp.cms_module_id', '=', 'cms_module.id')
                                ->where('cmp.role_id', '=', $role_id);
                        });
                    $query->where('status', '1')->orderBy('sort_order', 'asc');
                }
            ])
            ->leftJoin('cms_module_permission AS cmp', function ($leftJoin) use ($role_id) {
                $leftJoin->on('cmp.cms_module_id', '=', 'cms_module.id')
                    ->where('cmp.role_id', '=', $role_id);
            })
            ->where('status', '1')
            ->orderBy('sort_order', 'asc')
            ->get();
        return $query;
    }

    public static function getModules($role_id)
    {
        // Step 1: Get all modules that have permission for this role
        $query = DB::table('cms_module_permission AS cmp')
            ->join('cms_module AS cm', 'cm.id', '=', 'cmp.module_id')
            ->select(
                'cm.id',
                'cm.name',
                'cm.route_name',
                'cm.icon',
                'cm.sort_order',
                'cm.parent_id',
                'cmp.is_add',
                'cmp.is_view',
                'cmp.is_update',
                'cmp.is_delete'
            )
            ->where('cmp.role_id', $role_id)
            ->where('cm.status', 'active')
            ->where(function ($q) {
                $q->where('cmp.is_add', 1)
                    ->orWhere('cmp.is_view', 1)
                    ->orWhere('cmp.is_update', 1)
                    ->orWhere('cmp.is_delete', 1);
            })
            ->orderBy('cm.sort_order', 'asc')
            ->get();

        if ($query->isEmpty()) {
            return [];
        }

        // Step 2: Group modules by parent_id
        $modulesByParent = [];
        foreach ($query as $module) {
            $modulesByParent[$module->parent_id][] = $module;
        }

        // Step 3: Build hierarchy (attach children to parents)
        $main_modules = [];
        if (isset($modulesByParent[0])) {
            foreach ($modulesByParent[0] as $parent) {
                // Attach submenu if exist, but filter based on parent permissions
                $children = $modulesByParent[$parent->id] ?? [];
                $filteredChildren = [];
                
                foreach ($children as $child) {
                    // For index/list routes: child needs is_view = 1 AND parent needs is_view = 1
                    if (strpos($child->route_name, 'index') !== false) {
                        if ($child->is_view == 1 && $parent->is_view == 1) {
                            $filteredChildren[] = $child;
                        }
                    }
                    // For create/add routes: child needs is_add = 1 AND parent needs is_add = 1
                    elseif (strpos($child->route_name, 'create') !== false) {
                        if ($child->is_add == 1 && $parent->is_add == 1) {
                            $filteredChildren[] = $child;
                        }
                    }
                    // For other routes, check if child has any permission
                    else {
                        if ($child->is_add == 1 || $child->is_view == 1 || $child->is_update == 1 || $child->is_delete == 1) {
                            $filteredChildren[] = $child;
                        }
                    }
                }
                
                $parent->children = $filteredChildren;

                // ✅ Parent should appear if:
                //  - itself has is_view = 1 (for sidebar visibility), OR
                //  - it has visible children
                $hasViewPermission = ($parent->is_view == 1);
                $hasChildren = !empty($parent->children);

                if ($hasViewPermission || $hasChildren) {
                    $main_modules[] = $parent;
                }
            }
        }

        // Step 4: return full structured list
        return $main_modules;
    }
}
