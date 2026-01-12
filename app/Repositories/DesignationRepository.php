<?php

namespace App\Repositories;

use Exception;
use App\Models\Designation;

class DesignationRepository extends BaseRepository
{
    protected $model;

    /**
     * Method __construct
     *
     * @param Designation $model [explicite description]
     *
     * @return void
     */
    public function __construct(Designation $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * Method getAllDesignation
     *
     * @return void
     */
    public function getAllDesignation_pre()
    {
        $designationList = Designation::all();;
        return $designationList;
    }

    // public function getAllDesignation(array $data, $paginate = true): object
    // {
    //     $sortFields = [
    //         'id' => 'id',
    //         'name' => 'name',
    //     ];

    //     $search = trim($data['search'] ?? '');
    //     $offset = $data['start'] ?? 0;
    //     $limit = $data['size'] ?? config('constants.pagination_limit.defaultPagination');
    //     $sort = $sortFields[$data['sortColumn'] ?? 'name'] ?? 'name';
    //     $sortDirection = $data['sortDirection'] ?? 'asc';

    //     $query = $this->model->newQuery()->select('id', 'name');

    //     if ($search !== '') {
    //         $query->where('name', 'like', $search . '%');
    //     }

    //     $query->orderBy($sort, $sortDirection)
    //         ->offset($offset)
    //         ->limit($limit);

    //     return $query->get();
    // }

    
    public function getAllDesignation(array $data, $paginate = true): object
    {
        $sortFields = [
            'id' => 'id',
            'name' => 'name',
        ];

        $search = trim($data['search'] ?? '');
        $offset = $data['start'] ?? 0;
        $limit = $data['size'] ?? config('constants.pagination_limit.defaultPagination');
        $sort = $sortFields[$data['sortColumn'] ?? 'name'] ?? 'name';
        $sortDirection = $data['sortDirection'] ?? 'asc';

        $query = $this->model->newQuery()->select('id', 'name');

        if ($search !== '') {
            $query->where('name', 'like', $search . '%');
        }

        /**
         * ✅ TRUE A–Z SORT (letters first, numbers last)
         */
        if ($sort === 'name') {
            $query->orderByRaw("
                CASE 
                    WHEN name REGEXP '^[A-Za-z]' THEN 0 
                    ELSE 1 
                END ASC
            ");

            $query->orderByRaw("
                REGEXP_REPLACE(name, '^[^A-Za-z]+', '') 
                COLLATE utf8mb4_unicode_ci {$sortDirection}
            ");
        } else {
            $query->orderBy($sort, $sortDirection);
        }

        $query->offset($offset)->limit($limit);

        return $query->get();
    }

    
}
