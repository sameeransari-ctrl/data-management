<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    /**
     * Eloquent Model
     *
     * @var Model
     */
    protected $model;
    
    /**
     * Method __construct
     *
     * @param Model $model [explicite description]
     *
     * @return void
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }
    
    /**
     * Method create
     *
     * @param array $attributes [explicite description]
     *
     * @return object
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * Get all record
     *
     * @return Collection
     */
    public function getAll()
    {
        return $this->model->get();
    }
    
    /**
     * Method getAllWhere
     *
     * @param array $where [explicite description]
     * @param $with $with [explicite description]
     *
     * @return void
     */
    public function getAllWhere(array $where, $with = [])
    {
        return $this->model->with($with)->where($where)->get();
    }
    
    /**
     * Method updateOrCreate
     *
     * @param array $where [explicite description]
     * @param array $attributes [explicite description]
     *
     * @return object
     */
    public function updateOrCreate(array $where, array $attributes)
    {
        return $this->model->updateOrCreate($where, $attributes);
    }
    
    /**
     * Method firstOrCreate
     *
     * @param array $where [explicite description]
     * @param array $attributes [explicite description]
     *
     * @return void
     */
    public function firstOrCreate(array $where, array $attributes)
    {
        return $this->model->firstOrCreate($where, $attributes);
    }
    
    /**
     * Method firstWhere
     *
     * @param array $where [explicite description]
     *
     * @return object
     */
    public function firstWhere(array $where)
    {
        return $this->model->where($where)->first();
    }

    /**
     * Update Specified resource
     *
     * @param array $data  
     * @param int   $id
     * @param Model $model  
     *  
     * @return Model
     */
    public function update(array $data, int $id, ?Model $model=null):Model
    {
        $model = $model ?? $this->model->find($id);
        if ($model) {
            $isUpdated = $model->update($data);
            if ($isUpdated) {
                $model = $this->model->find($id);
            }
        }
        return $model;
    }
    
    /**
     * Method findWith
     *
     * @param int $id [explicite description]
     * @param array $with [explicite description]
     *
     * @return void
     */
    public function findWith(int $id, array $with = [])
    {
        return $this->model->with($with)->where(['id' => $id])->first();
    }
    
    /**
     * Method deleteWhere
     *
     * @param $id $id [explicite description]
     *
     * @return void
     */
    public function deleteWhere($id)
    {
        return $this->model->destroy($id);
    }
}
