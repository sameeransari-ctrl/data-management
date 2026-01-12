<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Repositories\RoleRepository;
use App\Http\Requests\Admin\RoleRequest;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    protected $roleRepository;

    /**
     * Method __construct
     *
     * @param RoleRepository $roleRepository [explicite description]
     *
     * @return void
     */
    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }


    /**
     * Method index
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function index(Request $request)
    {
        $data = $request->all();
        if ($request->ajax()) {
            return RoleResource::collection(
                $this->roleRepository->getRole($data)
            );
        }
        return view('admin.role.index');
    }


    /**
     * Method create
     *
     * @return void
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('admin.role._add', compact('permissions'));
    }


    /**
     * Method store
     *
     * @param RoleRequest $request [explicite description]
     *
     * @return void
     */
    public function store(RoleRequest $request)
    {
        $params = $request->validated();
        $result = $this->roleRepository->createRole($params);
        if (!empty($result)) {
            return $this->successResponse(
                __(
                    'message.success.added',
                    [
                        'type' => __('labels.role')
                    ]
                )
            );
        }
    }

    /**
     * Method show
     *
     * @param string $id [explicite description]
     *
     * @return void
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Method edit
     *
     * @param Role $role [explicite description]
     *
     * @return void
     */
    public function edit(Role $role)
    {
        $rolePermissions = $this->roleRepository->getRoleHasPermissions($role->id);
        $permissions = Permission::all();
        return view('admin.role._edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Method update
     *
     * @param RoleRequest $request [explicite description]
     * @param Role        $role    [explicite description]
     *
     * @return void
     */
    public function update(RoleRequest $request, Role $role)
    {
        $params = $request->validated();
        $result = $this->roleRepository->updateRole($params, $role->id, $role);
        if (!empty($result)) {
            return $this->successResponse(
                __(
                    'message.success.updated',
                    [
                        'type' => __('labels.role')
                    ]
                )
            );
        }
    }

    /**
     * Method destroy
     *
     * @param string $id [explicite description]
     *
     * @return void
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Method changeStatus
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function changeStatus(Request $request)
    {
        $params = $request->all();
        $role = $this->roleRepository->changeStatus($params, $params['id']);
        $status = $params['status'] == '1' ? User::STATUS_ACTIVE : User::STATUS_INACTIVE;
        return $this->successResponse(trans('message.success.'. $status));
    }
}
