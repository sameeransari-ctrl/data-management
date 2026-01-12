<?php

namespace App\Repositories;

use Exception;
use App\Models\CompanyWebsite;

class CompanyWebsitesRepository extends BaseRepository
{
    protected $model;

    /**
     * Method __construct
     *
     * @param CompanyWebsite $model [explicite description]
     *
     * @return void
     */
    public function __construct(CompanyWebsite $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    
    public function getAllCompanyWebsites(array $data, $paginate = true): object
    {
        // Sortable fields
        $sortFields = [
            'id' => 'id',
            'website' => 'website',        // sort by website column
            'normalized_key' => 'normalized_key', // sort by normalized_key
        ];

        $search = $data['search'] ?? '';
        $offset = $data['start'] ?? 0;
        $sortDirection = $data['sortDirection'] ?? 'asc';
        $sort = $sortFields['id'];
        $limit = $data['size'] ?? config('constants.pagination_limit.defaultPagination');

        if (array_key_exists('sortColumn', $data) && isset($sortFields[$data['sortColumn']])) {
            $sort = $sortFields[$data['sortColumn']];
        }

        $query = $this->model->newQuery();

        // Apply search on website and normalized_key
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('website', 'like', '%' . $search . '%')
                ->orWhere('normalized_key', 'like', '%' . $search . '%');
            });
        }

        // Apply ordering, offset, and limit
        $query->orderBy($sort, $sortDirection)
            ->offset($offset)
            ->limit($limit);

        return $query->get();
    }


    
}
