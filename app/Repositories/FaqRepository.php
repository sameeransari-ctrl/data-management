<?php

namespace App\Repositories;

use App\Models\Faq;

/**
 * Class CrudCategoryRepository.
 */
class FaqRepository extends BaseRepository
{
    protected $model;

    /**
     * Method __construct
     *
     * @param  Faq  $model [explicite description]
     * @return void
     */
    public function __construct(Faq $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * Method getFaq
     */
    public function getFaq(array $data, $paginate = true): object
    {
        $sortFields = [
            'id' => 'id',
            'question' => 'question',
            'answer' => 'answer',
        ];

        $search = $data['search'] ?? '';
        $offset = $data['start'] ?? '';
        $sortDirection = $data['sortDirection'] ?? 'asc';
        $sort = $sortFields['id'];

        if (array_key_exists('sortColumn', $data) && isset($sortFields[$data['sortColumn']])) {
            $sort = $sortFields[$data['sortColumn']];
        }

        $limit = $data['size'] ?? config('constants.pagination_limit.defaultPagination');
        $this->model->offset($offset);
        $this->model->limit($limit);

        $faq = $this->model
            ->when(
                $search,
                function ($q) use ($search) {
                    $q->where('question', 'like', '%'.$search.'%')
                        ->orWhere('answer', 'like', '%'.$search.'%');
                }
            );

        if (! $paginate) {
            $result = $faq->orderBy($sort, $sortDirection)->get();
        } else {
            $result = $faq->orderBy($sort, $sortDirection)->paginate($limit);
        }

        return $result;
    }

    /**
     * Method createCategory
     *
     * @param  array  $data [explicite description]
     * @return object
     */
    public function createFaq(array $data)
    {
        return $this->create($data);
    }

    /**
     * Function GetFaq
     *
     * @param  int  $id
     * @return object
     */
    public function getFaqDetail($id)
    {
        return $this->model->find($id);
    }

    /**
     * Method updateFaq
     *
     * @param  array  $data [explicite description]
     * @return object|bool
     */
    public function updateFaq(array $data)
    {
        return $this->updateOrCreate(
            [
                'id' => $data['id'],
            ],
            $data
        );
    }

    /**
     * Method deleteFaq
     *
     * @param $id $id [explicite description]
     * @return bool
     */
    public function deleteFaq($id)
    {
        return $this->model->find($id)->delete();
    }
}
