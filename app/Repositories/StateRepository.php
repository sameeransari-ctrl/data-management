<?php

namespace App\Repositories;

use App\Models\State;
use App\Repositories\Interfaces\StateRepositoryInterface;

class StateRepository implements StateRepositoryInterface
{
    protected $model;

    /**
     * @var Model
     */
    public function __construct(State $model)
    {
        $this->model = $model;
    }
   
    /**
     * Method get state list by country id.
     *
     * @param array $data [explicite description]
     *
     * @return array
     */
    public function stateList(array $data)
    {
        return $this->model->where(['country_id' => $data['country_id']])->get();
    }
}