<?php

namespace App\Repositories;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\UserActivity;

class UserActivityRepository extends BaseRepository
{
    protected $model;

    /**
     * Method __construct
     *
     * @param UserActivity $model [explicite description]
     *
     * @return void
     */
    public function __construct(UserActivity $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * Method getUserActivity
     *
     * @param int $id [explicite description]
     *
     * @return UserActivity
     */
    public function getUserActivity(int $id): ?UserActivity
    {
        return $this->model->find($id);
    }

    /**
     * Method getUserActivityByField
     *
     * @param $where $where
     *
     * @return UserActivity|null
     */
    public function getUserActivityByField($where): ?UserActivity
    {
        return $this->model->where($where)->first();
    }
                
    /**
     * Method userActivityUpdateOrCreate
     *
     * @param array $data [explicite description]
     *
     * @return UserActivity
     */
    public function userActivityUpdateOrCreate(array $data, $userId): UserActivity
    {
        $userActivity = $this->firstWhere(['user_id' => $userId]);
        if (!empty($userActivity)) {
            $newActivityTime = $userActivity->activity_time + $data['activity_time'];
            return $this->update(['activity_time' => $newActivityTime], $userActivity->id);
        } else {
            $newData = [
                    'activity_time' => $data['activity_time'],
                    'user_id' => $userId
                ];
            return $this->create($newData);
        }
    }

    
    /**
     * Method getActiveUserList
     *
     * @return void
     */
    public function getActiveUserList()
    {
        return $this->model->leftJoin('users', 'users.id', 'user_activities.user_id')
            ->where(
                function ($q) {
                    $q->where('users.user_type', User::TYPE_MEDICAL)
                        ->orWhere('users.user_type', User::TYPE_BASIC);
                }
            )
            ->where(
                function ($q) {
                    $q->whereNotNull('users.email_verified_at')
                        ->orWhereNotNull('users.phone_number_verified_at');
                }
            )
            ->where('users.status', User::STATUS_ACTIVE)
            ->orderBy('user_activities.activity_time', 'DESC')
            ->take(10)
            ->get();
    }
    
}
