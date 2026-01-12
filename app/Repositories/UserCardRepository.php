<?php

namespace App\Repositories;

use App\Models\UserCard;

class UserCardRepository extends BaseRepository
{
    protected $model;

    /**
     * Method __construct
     */
    public function __construct(UserCard $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

        
        
    /**
     * Method getUserCard
     *
     * @param int $userId [explicite description]
     *
     * @return UserCard
     */
    public function getUserCard(int $userId): ?UserCard
    {
        return $this->model->where('user_id', $userId)->first();
    }

}
