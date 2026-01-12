<?php

namespace App\Repositories;

use Exception;
use App\Models\ProductQuestion;

class ProductQuestionRepository extends BaseRepository
{
    protected $model;

    /**
     * Method __construct
     *
     * @param ProductQuestion $model 
     * 
     * @return void 
     */
    public function __construct(ProductQuestion $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * Method saveProductQuestion
     *
     * @param array $data 
     *
     * @return object
     */
    public function saveProductQuestion(array $data)
    {
        if ($data['answer_type'] == ProductQuestion::PRODUCT_ANSWER_TYPE_CHECK_BOX_TEXT) {
            $data['answer_options'] = json_encode($data['answer_options_check_box']);
            $data['default_answer'] = (!empty($data['answer_options_check_box_default_answer'])) ? json_encode($data['answer_options_check_box_default_answer']) : null;
        } else if ($data['answer_type'] == ProductQuestion::PRODUCT_ANSWER_TYPE_RADIO_BUTTON_TEXT) {
            $data['answer_options'] = json_encode($data['answer_options_radio_button']);
            $data['default_answer'] = (!empty($data['answer_options_radio_button_default_answer'])) ? json_encode($data['answer_options_radio_button_default_answer']) : null;
        } else if ($data['answer_type'] == ProductQuestion::PRODUCT_ANSWER_TYPE_INPUT_FIELD_TEXT) {
            $data['answer_options'] = null;
            $data['default_answer'] = null;
        }
        
        $data['answer_type'] = ProductQuestion::$productAnswerTypeValue[$data['answer_type']]; 
        $id = (!empty($data['id'])) ? $data['id'] : null;

        //echo '<pre>'; print_r($data); die('>>>');
        

        return $this->updateOrCreate(['id'=>$id], $data);
    }

    /**
     * Method getSingleProductQuestion
     *
     * @param $questionId $questionId 
     *
     * @return void
     */
    public function getSingleProductQuestion($questionId)
    {
        $query = $this->model->where(['id' => $questionId]);
        $data = $query->get()->toArray();

        return $data;
    }
  
    /**
     * Method getAllProductQuestion
     *
     * @param int $productId 
     * @param int $questionType 
     *
     * @return void
     */
    public function getAllProductQuestion(int $productId, int $questionType)
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
    
    /**
     * Method getQuestions
     *
     * @param int $productId    [explicite description]
     * @param int $questionType [explicite description]
     *
     * @return collection
     */
    public function getQuestions($productId, $questionType)
    {   
        return $this->model
            ->where(['product_id' => $productId, 'question_type' => $questionType])
            ->get();
    }
}
