<?php

namespace App\Repositories;
use App\Models\ClientRole;

class ClientRoleRepository extends BaseRepository
{
    protected $model;
        
    /**
     * Method __construct
     *
     * @param ClientRole $model [explicite description]
     *
     * @return void
     */
    public function __construct(ClientRole $model)
    {
        parent::__construct($model);
        $this->model = $model;
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
     * Method getrole
     *
     * @param $id $id [explicite description]
     *
     * @return void
     */
    public function getRole($id)
    {
        return $this->model->where('id', $id)->first('name');
    }
}
