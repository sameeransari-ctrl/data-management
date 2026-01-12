<?php

namespace App\Repositories;

use App\Models\Product;
use Exception;
use App\Models\RatingReview;
use App\Models\RatingReviewClass;
use App\Models\User;
use App\Notifications\ProductRatingReview;

class RatingReviewRepository extends BaseRepository
{
    protected $model;

    /**
     * Method __construct
     *
     * @param RatingReview $model
     *
     * @return void
     */
    public function __construct(RatingReview $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * Method createRatingReview
     *
     * @param array $data [explicite description]
     *
     * @return RatingReview
     */
    public function createRatingReview(array $data)
    {
        $ratingReview = $this->firstWhere(['review_by' => $data['review_by'], 'product_id' => $data['product_id']]);
        if (!empty($ratingReview)) {
            return false;
        }

        $ratingReview = $this->create($data);

        //start sent notification to admin about feedback received of products.
        $userData = User::onlyAdmin()->first();
        $productData = Product::where('id', $ratingReview['product_id'])->first();
        $userData->notify(new ProductRatingReview($productData));
        $productData->client->notify(new ProductRatingReview($productData));
        //end sent notification to admin about feedback received of products.

        return $ratingReview;
    }

    /**
     * Method getRatingAverage
     *
     * @param $productId $productId [explicite description]
     *
     * @return integer
     */
    public function getRatingAverage($productId)
    {
        return $this->model->where('product_id', $productId)->avg('rating');
    }

    /**
     * Method getRatingReview
     *
     * @param $productId $productId [explicite description]
     *
     * @return void
     */
    public function getRatingReviews($productId)
    {
        return $this->model->where('product_id', $productId)->orderBy('id', 'desc')->get();
    }

    /**
     * Method getUserRatingReviewCount
     *
     * @param $userId $userId [explicite description]
     *
     * @return void
     */
    public function getUserRatingReviewCount($userId)
    {
        return $this->model->where('review_by', $userId)->count();
    }

    /**
     * Method getTotalReviewCount
     *
     * @param $clientId $clientId [explicite description]
     *
     * @return void
     */
    public function getTotalReviewCount($clientId)
    {
        return $this->model
            ->leftJoin('products', 'products.id', 'rating_reviews.product_id')
            ->where('products.client_id', $clientId)
            ->count();
    }
}
