<?php

namespace App\Repositories;

use App\Models\User;
use App\Notifications\CreateStaff;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Notifications\WelcomeStaff;
use Illuminate\Support\Facades\Auth;

/**
 * Class CrudCategoryRepository.
 */
class StaffRepository extends BaseRepository
{
    protected $model;

    /**
     * Method __construct
     *
     * @param User $model [explicite description]
     *
     * @return void
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }


    /**
     * Method getUser
     *
     * @param int $id [explicite description]
     *
     * @return User
     */
    public function getUser(int $id): ?User
    {
        return $this->model->find($id);
    }


    /**
     * Method getStaff
     *
     * @param array $data [explicite description]
     * @param $paginate $paginate [explicite description]
     *
     * @return object
     */
    public function getStaff(array $data, $paginate = true): object
    {
        $sortFields = [
            'id' => 'id',
            'name' => 'name',
            'phone_number' => 'phone_number',
            'user_type' => 'user_type',
        ];
        $export = $data['export'] ?? '';
        $search = $data['search'] ?? '';
        $name = $data['name'] ?? '';
        $email = $data['email'] ?? '';
        $status = $data['status'] ?? '';
        $type = $data['type'] ?? '';
        $offset = $data['start'] ?? '';
        $sortDirection = $data['sortDirection'] ?? 'desc';
        $sort = $sortFields['id'];

        if (array_key_exists('sortColumn', $data) && isset($sortFields[$data['sortColumn']])) {
            $sort = $sortFields[$data['sortColumn']];
        }
        if (isset($export) && $export == 'export') {
            $paginate = false;
        }
        $limit = $data['size'] ?? config('constants.pagination_limit.defaultPagination');
        $this->model->offset($offset);
        $this->model->limit($limit);

        $staff = $this->model
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->when(
                $search,
                function ($q) use ($search) {
                    $q->where(
                        function ($q) use ($search) {
                            $q->where('name', 'like', '%'.$search.'%')
                                ->orWhere('email', 'like', '%'.$search.'%')
                                ->orWhere(DB::raw("CONCAT(phone_code, phone_number)"), 'like', '%'.$search.'%');
                        }
                    );
                }
            )
            ->when(
                $name,
                function ($q) use ($name) {
                    $q->where('name', 'like', '%'.$name.'%');
                }
            )
            ->when(
                $email,
                function ($q) use ($email) {
                    $q->where('email', 'like', '%'.$email.'%');
                }
            )
            ->when(
                $status,
                function ($q) use ($status) {
                    $q->where('status', $status);
                }
            )
            ->when(
                $type,
                function ($q) use ($type) {
                    $q->where('role_id', $type);
                }
            )
            ->when(
                (!empty($data['not_current_staff']) && auth()->check()),
                function ($q) use ($type) {
                    $q->where('id', '<>', auth()->user()->id);
                }
            )
            ->where('user_type', User::TYPE_STAFF)
            ->orderBy($sort, $sortDirection);


        if (! $paginate) {
            $result = $staff->orderBy($sort, $sortDirection)->get();
        } else {
            $result = $staff->orderBy($sort, $sortDirection)->paginate($limit);
        }

        return $result;
    }


    /**
     * Method createStaff
     *
     * @param array $data [explicite description]
     *
     * @return void
     */
    public function createStaff(array $data)
    {
        $roleId =  $data['user_type'];
        if (!empty($data['profile_image'])) {
            $data['profile_image'] = uploadFile(
                $data['profile_image'],
                config('constants.image.profile.path')
            );
        }
        $data['status'] = User::STATUS_ACTIVE;
        $data['user_type'] = User::TYPE_STAFF;
        $user = $this->create($data);
        $userType = Auth::user()->user_type;
        if (($userType == User::TYPE_STAFF)) {
            $userData = User::onlyAdmin()->first();
            $userData->notify(new CreateStaff());
        }
        $user->assignRole($roleId);
        $user->notify(new WelcomeStaff($user, $data['password']));
        return $user;

    }


    /**
     * Method getStaffDetail
     *
     * @param $id $id [explicite description]
     *
     * @return object
     */
    public function getStaffDetail($id)
    {
        return $this->model->find($id);
    }


    /**
     * Method updateStaff
     *
     * @param array $data [explicite description]
     *
     * @return int
     */
    public function updateStaff(array $data, int $id, ?User $user = null): User
    {
        $roleId =  $data['user_type'];
        if (! $user) {
            $user = $this->getUser($id);
        }

        if (! empty($user) && ! empty($data['profile_image'])) {
            $data['profile_image'] = uploadFile(
                $data['profile_image'],
                config('constants.image.profile.path')
            );
            if (! empty($user->profile_image)) {
                deleteFile($user->profile_image);
            }
        } else {
            unset($data['profile_image']);
        }

        if (empty($data['password'])) {
            unset($data['password']);
        } 
    
        $data['user_type'] = User::TYPE_STAFF;
        $updated = $this->update($data, $id);
        if ($updated) {
            $userUpdate = $this->getUser($user->id);
        }

        DB::table('model_has_roles')->where('model_id', $id)->delete();
        $userUpdate->assignRole($roleId);
        return $userUpdate;
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
            $user = $this->getUser($id);
            if (!empty($user)) {
                return $this->update($data, $user->id, $user);
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Method removeUserSession
     *
     * @param User $user [explicite description]
     *
     * @return void
     */
    public function removeUserSession(User $user)
    {
        $user->should_re_login = 1;
        $user->save();
    }

    public function getTotalActiveStaffs()
    {
        return $this->model->where('status', User::STATUS_ACTIVE)
            ->where('user_type', User::TYPE_STAFF)
            ->get();
    }

    /**
     * Method getTotalInactiveUsers
     *
     * @return void
     */
    public function getTotalInactiveStaffs()
    {
        return $this->model->where('status', User::STATUS_INACTIVE)
            ->where('user_type', User::TYPE_STAFF)
            ->get();
    }

}
