<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Models\Role as UserRole;
use App\Models\User;
use App\Notifications\CreateRole;
use Illuminate\Support\Facades\Auth;

/**
 * Class RoleRepository.
 */
class RoleRepository extends BaseRepository
{
    protected $model;

    /**
     * Method __construct
     *
     * @param Role $model [explicite description]
     *
     * @return void
     */
    public function __construct(Role $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * Method createRole
     *
     * @param array $data [explicite description]
     *
     * @return Role
     */
    public function createRole(array $data): Role
    {
        $role = $this->create($data);
        $userType = Auth::user()->user_type;
        if ($role) {
            if (($userType == User::TYPE_STAFF)) {
                $user = User::onlyAdmin()->first();
                $user->notify(new CreateRole());
            }
            $role->syncPermissions($data['permissions']);
        }
        return $role;
    }

    /**
     * Method getRole
     *
     * @param array $data     [explicite description]
     * @param $paginate $paginate [explicite description]
     *
     * @return object
     */
    public function getRole(array $data, $paginate = true): object
    {
        $sortFields = [
            'id' => 'id',
            'name' => 'name',
        ];

        $search = $data['search'] ?? '';
        $offset = $data['start'] ?? '';
        $sortDirection = $data['sortDirection'] ?? 'asc';
        $sort = $sortFields['id'];

        if (array_key_exists('sortColumn', $data) && isset($sortFields[$data['sortColumn']])) {
            $sort = $sortFields[$data['sortColumn']];
        }

        $limit = $data['size'] ?? config('constants.pagination_limit.defaultPagination');
        $this->model->offset($offset);
        $this->model->limit($limit);

        $role = $this->model
            ->when(
                $search,
                function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                }
            )
            ->when(
                isset($data['status']),
                function ($q) use ($data) {
                    $q->where('status', $data['status']);
                }
            )
            ->where(
                function ($q) {
                    $q->where('name', '<>', UserRole::TYPE_SUPER_ADMIN)
                        ->Where('name', '<>', UserRole::TYPE_ADMIN);
                }
            );
        if (!$paginate) {
            $result = $role->orderBy($sort, $sortDirection)->get();
        } else {
            $result = $role->orderBy($sort, $sortDirection)->paginate($limit);
        }
        return $result;
    }

    /**
     * Method getRoleById
     *
     * @param int $id [explicite description]
     *
     * @return Role
     */
    public function getRoleById(int $id): ?Role
    {
        return $this->model->findById($id);
    }

    /**
     * Method changeStatus
     *
     * @param array $data [explicite description]
     * @param int   $id   [explicite description]
     *
     * @return void
     */
    public function changeStatus(array $data, int $id)
    {
        try {
            $role = $this->getRoleById($id);
            if (!empty($role)) {
                $role = $this->update($data, $role->id, $role);
                return $role->users()->update(['should_re_login' => $data['status'] == 1 ? 0 : 1]);
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Method updateRole
     *
     * @param array $data [explicite description]
     * @param int   $id   [explicite description]
     * @param ?Role $role [explicite description]
     *
     * @return Role
     */
    public function updateRole(array $data, int $id, ?Role $role = null): Role
    {
        if (!$role) {
            $role = $this->getRoleById($id);
        }
        $updated = $this->update($data, $id);
        if ($updated) {
            if (isset($data['permissions'])) {
                $role->syncPermissions($data['permissions']);
            }
        }

        return $role;
    }

    /**
     * Method getRoleHasPermissions
     *
     * @param $id $id [explicite description]
     *
     * @return void
     */
    public function getRoleHasPermissions($id)
    {
        return DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();
    }

    /**
     * Method getAll
     *
     * @return void
     */
    public function getAll()
    {
        return $this->model->get();
    }

    /**
     * Method roleList
     *
     * @return void
     */
    public function roleList()
    {
        return $this->model
            ->where('name', '<>', UserRole::TYPE_SUPER_ADMIN)
            ->where('name', '<>', UserRole::TYPE_ADMIN)
            ->where('status', 1)
            ->get();
    }

    /**
     * Method allRoleList
     *
     * @return void
     */
    public function allRoleList()
    {
        return $this->model
            ->where('name', '<>', UserRole::TYPE_SUPER_ADMIN)
            ->where('name', '<>', UserRole::TYPE_ADMIN)
            ->get();
    }

    /**
     * Method getActiveRoleById
     *
     * @param int $id [explicite description]
     *
     * @return Role
     */
    public function getInActiveRoleById(int $id): ?Role
    {
        return $this->model
            ->where('id', $id)
            ->where('status', UserRole::STATUS_INACTIVE)
            ->first();
    }

    public function getTotalActiveRoles()
    {
        return $this->model->where('status', UserRole::STATUS_ACTIVE)
            ->where('name', '<>', UserRole::TYPE_SUPER_ADMIN)
            ->where('name', '<>', UserRole::TYPE_ADMIN)
            ->get();
    }

    /**
     * Method getTotalInactiveUsers
     *
     * @return void
     */
    public function getTotalInactiveRoles()
    {
        return $this->model->where('status', UserRole::STATUS_INACTIVE)
            ->where('name', '<>', UserRole::TYPE_SUPER_ADMIN)
            ->where('name', '<>', UserRole::TYPE_ADMIN)
            ->get();
    }
}
