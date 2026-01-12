<?php

namespace App\Repositories;

use App\Models\City;

class CityRepository extends BaseRepository
{
    protected $model;
    
    /**
     * Method __construct
     *
     * @param City $model [explicite description]
     *
     * @return void
     */
    public function __construct(City $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
    
    /**
     * Method cityList
     *
     * @param array $data [explicite description]
     *
     * @return void
     */
    public function cityList(array $data)
    {

        if (! empty($data['country_id'])) {
            return $this->model->where(['country_id' => $data['country_id']])->orderBy('name', 'asc')->get();
        }
        if (! empty($data['state_id'])) {
            return $this->model->where(['state_id' => $data['state_id']])->orderBy('name', 'asc')->get();
        }
    }
    
    /**
     * Method getCities
     *
     * @param array $data [explicite description]
     *
     * @return void
     */
    public function getCities(array $data)
    {
        return $this->model->where('state_id', $data['state_id'])->get()->pluck('name', 'id');
    }
}
