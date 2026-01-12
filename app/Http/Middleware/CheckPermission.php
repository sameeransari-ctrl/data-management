<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Exceptions\UnauthorizedException;

class CheckPermission
{
    /**
     * Method handle
     *
     * @param Request $request    [explicite description]
     * @param Closure $next       [explicite description]
     * @param string  $permission $permission — [explicite description]
     * @param $guard      $guard [explicite description]
     *
     * @return void
     */
    public function handle(Request $request, Closure $next, $permission = null, $guard = null)
    {
        $authGuard = app('auth')->guard($guard);

        if ($authGuard->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        if (!is_null($permission)) {
            $permissions = is_array($permission)
                ? $permission
                : explode('|', $permission);
        }

        if (is_null($permission)) {
            $permission = $request->route()->getName();
            $permissions = array($permission);
        }

        foreach ($permissions as $permission) {

            $permission = $this->getPermissionString($permission, $authGuard);
            if ($authGuard->user()->can($permission)) {
                return $next($request);
            }
        }

        throw UnauthorizedException::forPermissions($permissions);
    }

    /**
     * Method getPermissionString
     *
     * @param string \App\Http\Middleware\ $permission $permission — [explicite description]
     *
     * @return void
     */
    protected function getPermissionString($permission, $authGuard)
    {
        $exploded_route_name = explode(".", $permission);
        if (count($exploded_route_name) == 3) {
            $replacer_array = [
                'store' => 'create',
                'update' => 'edit',
                'changeStatus' => 'edit',
                'rating-details' => 'show',
                'export-csv' => 'index'
            ];
            $updateArray = [];
            if ($authGuard->user()->can('admin.product.create')) {
                $updateArray = [
                        'add-question' => 'create',
                        'store-question' => 'create',
                        'get-questions' => 'create',
                        'edit-question' => 'create',
                        'update-question' => 'create',
                        'update-product-status' => 'create',
                        'destroy-question' => 'create'
                    ];
            } else if ($authGuard->user()->can('admin.product.edit')) {
                $updateArray = [
                    'add-question' => 'edit',
                    'store-question' => 'edit',
                    'get-questions' => 'edit',
                    'edit-question' => 'edit',
                    'update-question' => 'edit',
                    'update-product-status' => 'edit',
                    'destroy-question' => 'edit'
                ];
            }
            $replacer_array = (!empty($updateArray)) ? array_merge($replacer_array, $updateArray) : $replacer_array;
            /*             
            * if the index is exist in replacer array the replace the value with replacer array value           
            * otherwise take the default value           
            */

            if (array_key_exists($exploded_route_name[2], $replacer_array)) {
                return $exploded_route_name[0] . '.' . $exploded_route_name[1] . '.' . $replacer_array[$exploded_route_name[2]];
            }
            return $permission;
        }
    }
}
