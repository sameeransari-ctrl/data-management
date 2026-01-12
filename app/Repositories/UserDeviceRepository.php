<?php

namespace App\Repositories;

use App\Models\UserDevice;
use Exception;

class UserDeviceRepository extends BaseRepository
{
    protected $model;
    
    /**
     * Method __construct
     *
     * @param UserDevice $model [explicite description]
     *
     * @return void
     */
    public function __construct(UserDevice $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
    
    /**
     * Method createDevice
     *
     * @param array $data [explicite description]
     *
     * @return void
     */
    public function createDevice(array $data)
    {
        try {
            if (! $data['user_id']) {
                return null;
            }

            return $this->model->updateOrCreate(
                [
                    'user_id' => $data['user_id'],
                ],
                [
                    'device_id' => $data['device_id'],
                    'device_type' => $data['device_type'],
                    'device_version' => $data['device_version'],
                    'access_token' => $data['access_token'] ?? null,
                ]
            );
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * Method getDeviceByUser
     *
     * @param $userIds $userIds [explicite description]
     * @param $withUser $withUser [explicite description]
     *
     * @return void
     */
    public function getDeviceByUser($userIds, $withUser = false)
    {
        try {
            if (! is_array($userIds)) {
                $userIds = [$userIds];
            }
            $query = $this->model->whereIn('user_id', $userIds);
            if ($withUser) {
                $query->with('user');
            }

            return $query->get();
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * Method updateDeviceByUser
     *
     * @param array $data [explicite description]
     * @param int $id [explicite description]
     *
     * @return int
     */
    public function updateDeviceByUser(array $data, int $id): int
    {
        try {
            return $this->model->where('user_id', $id)->update($data, $id);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * Method deleteDevice
     *
     * @param int $id [explicite description]
     *
     * @return int
     */
    public function deleteDevice(int $id): int
    {
        try {
            return $this->model->where(['user_id' => $id])->delete();
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
