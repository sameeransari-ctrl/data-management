<?php

namespace App\Repositories;

use App\Models\Product;
use Exception;
use App\Models\ProductAnswer;
use App\Models\User;
use App\Notifications\ProductAnswered;

class ProductQuestionAnswerRepository extends BaseRepository
{
    protected $model;

    /**
     * Method __construct
     *
     * @param ProductAnswer $model
     *
     * @return void
     */
    public function __construct(ProductAnswer $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * Method saveProductAnswer
     *
     * @param array $data
     *
     * @return object
     */
    public function saveProductAnswer(array $data)
    {
        $result = $this->firstWhere(
            [
            'user_id' => $data['user_id'],
            'product_id' => $data['product_id'],
            'question_type' => $data['question_type']
            ]
        );

        if (!empty($result)) {
            return false;
        }
        if (!empty($data['questions'])) {
            $finalData = [];
            foreach ($data['questions'] as $row) {
                if ($row['answer_type'] == ProductAnswer::ANSWER_CHECKBOX) {
                    $row['answer_options'] = json_encode($row['answer_options']);
                } else if ($row['answer_type'] == ProductAnswer::ANSWER_RADIO) {
                    $row['answer_options'] = json_encode($row['answer_options']);
                } else if ($row['answer_type'] == ProductAnswer::ANSWER_INPUT) {
                    $row['answer_options'] = null;
                }

                $row['product_id'] = $data['product_id'];
                $row['question_type'] = $data['question_type'];
                $row['user_id'] = $data['user_id'];
                $finalData[] = $row;
            }
            if (!empty($finalData)) {
                // start sent notification to admin when answered of product by user.
                $userData = User::onlyAdmin()->first();
                $productData = Product::where('id', $data['product_id'])->first();
                $userData->notify(new ProductAnswered($productData));
                $productData->client->notify(new ProductAnswered($productData));
                // end sent notification to admin when answered of product by user.
                return ProductAnswer::insert($finalData);
            }
        }
        return true;

    }

    /**
     * Method getSingleProductAnswer
     *
     * @param $questionId $questionId
     *
     * @return void
     */
    public function getSingleProductAnswer($questionId)
    {
        $query = $this->model->where(['id' => $questionId]);
        $data = $query->get()->toArray();

        return $data;
    }

    /**
     * Method getAllProductAnswer
     *
     * @param int $productId
     * @param int $questionType
     *
     * @return void
     */
    public function getAllProductAnswer(int $productId, int $questionType)
    {
        $query = $this->model->where(['product_id' => $productId, 'question_type' => $questionType])->orderBy('id', 'ASC');
        $data = $query->get()->toArray();

        return $data;
    }

    /**
     * Method destroyQuestion
     *
     * @param $id $id
     *
     * @return bool
     */
    public function destroyQuestion($id)
    {
        return $this->model->find($id)->delete();
    }
}
