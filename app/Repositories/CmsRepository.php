<?php

namespace App\Repositories;

use App\Models\CmsPage;

/**
 * Class CmsRepository.
 */
class CmsRepository extends BaseRepository
{
    protected $model;

    /**
     * Method __construct
     *
     * @param  CmsPage  $model [explicite description]
     * @return void
     */
    public function __construct(CmsPage $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * Function getCmsDetails
     *
     * @param  int  $slug
     * @return object
     */
    public function getCmsDetails($slug)
    {
        return $this->firstWhere(['slug' => $slug]);
    }

    /**
     * Method updateCms
     *
     * @param  array  $data [explicite description]
     * @return bool|object
     */
    public function updateCms(array $data)
    {
        return $this->updateOrCreate(
            [
                'id' => $data['id'],
            ],
            $data
        );
    }
}
