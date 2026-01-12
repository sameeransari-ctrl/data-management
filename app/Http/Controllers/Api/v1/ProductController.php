<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Repositories\ProductRepository;
use App\Repositories\RatingReviewRepository;
use App\Repositories\ProductQuestionAnswerRepository;
use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductCompareResource;
use App\Http\Requests\Api\StoreProductRatingRequest;
use App\Http\Requests\Api\StoreProductQuestionAnwswerRequest;
use App\Repositories\ProductQuestionRepository;
use App\Repositories\ProductScannerRepository;
use App\Http\Resources\ProductQuestionResource;
use App\Models\ProductQuestion;

/**
 * ProductController
 */
class ProductController extends Controller
{
    
    protected $productRepository;
    protected $ratingReviewRepository;
    protected $productQuestionRepository;
    protected $productScannerRepository;
    protected $productQuestionAnswerRepository;
            
    /**
     * Method __construct
     *
     * @return void
     */
    public function __construct(
        ProductRepository $productRepository,
        RatingReviewRepository $ratingReviewRepository,
        ProductQuestionRepository $productQuestionRepository,
        ProductScannerRepository $productScannerRepository,
        ProductQuestionAnswerRepository $productQuestionAnswerRepository
    ) {
        $this->productRepository = $productRepository;
        $this->ratingReviewRepository = $ratingReviewRepository;
        $this->productQuestionRepository = $productQuestionRepository;
        $this->productScannerRepository = $productScannerRepository;
        $this->productQuestionAnswerRepository = $productQuestionAnswerRepository;
    }
    
    /**
     * Method index
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $user = auth()->user();
        $userId = isset($data['user_id']) ? $data['user_id'] : $user->id;
        $data['userId'] = $userId;
        $totalScanned = $this->productScannerRepository->getScannedProductsCount($data['userId']);
        $totalReviewed = $this->ratingReviewRepository->getUserRatingReviewCount($data['userId']);
        $products = ProductResource::collection(
            $this->productRepository->getScannedProductList($data)
        )
        ->additional(
            [
                'meta' => [
                    'totalScanned' => $totalScanned,
                    'totalReviewd' => $totalReviewed,
                ],
            ]
        );
        
        if ($products->isEmpty()) {
            return $this->successResponse(
                __('message.error.product.no_proudcts_found')
            );
        }
        
        return $products;
    }
    
    /**
     * Method create
     *
     * @return void
     */
    public function create()
    {
        //
    }
    
    /**
     * Method store
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function store(Request $request)
    {
        //
    }
            
    /**
     * Method show
     *
     * @param $udiNumber $udiNumber [explicite description]
     *
     * @return void
     */
    public function show($udiNumber)
    {
        $where = ["udi_number" => $udiNumber];
        $product = $this->productRepository->firstWhere($where);
        if (!empty($product)) {
            return $this->successResponse(
                __('product.productDetails'),
                new ProductDetailResource($product)
            );
        } else {
            return $this->errorResponse(
                __('message.error.product.no_proudcts_found')
            );
        }
    }
    
    /**
     * Method edit
     *
     * @param string $id [explicite description]
     *
     * @return void
     */
    public function edit(string $id)
    {
        //
    }
                
    /**
     * Method update
     *
     * @param Request $request [explicite description]
     * @param string $id [explicite description]
     *
     * @return void
     */
    public function update(Request $request, string $id)
    {
        //
    }
    
    /**
     * Method destroy
     *
     * @param string $id [explicite description]
     *
     * @return void
     */
    public function destroy(string $id)
    {
        //
    }
        
    /**
     * Method compareProduct
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function compareProduct(Request $request)
    {
        $products = [];
        $uidsStr = $request->uids;

        if (empty($request->uids)) {
            return $this->errorResponse(
                __('message.error.product.minimum_two_products_required')
            );
        }
        $uids = explode(',', $uidsStr);
        
        if (count($uids) == 1) {
            return $this->errorResponse(
                __('message.error.product.minimum_two_products_required')
            );
        }

        if (count($uids) > 3) {
            return $this->errorResponse(
                __('message.error.product.maximum_three_products_allowed')
            );
        }

        if (is_array($uids)) {
            foreach ($uids as $uid) {
                $where = ["udi_number" => $uid];
                $product = $this->productRepository->firstWhere($where);
                if (!empty($product)) {
                    $products[] = new ProductCompareResource($product);
                }
            }
        }
       
        if (!empty($products)) {
            return $this->successResponse(
                __('product.compareProductsDetails'),
                $products
            );
        } else {
            return $this->errorResponse(
                __('message.error.product.no_proudcts_found')
            );
        }
    }

    /**
     * Method storeProductRating
     *
     * @param StoreProductRatingRequest $request [explicite description]
     *
     * @return void
     */
    public function storeProductRating(StoreProductRatingRequest $request)
    {
        $post = $request->all();
        $post['review_by'] = auth()->user()->id;
        $rating = $this->ratingReviewRepository->createRatingReview($post);
        if ($rating) {
            return $this->successResponse(__('message.success.rating_stored_success'));
        }
        return $this->errorResponse(
            __('message.error.product.rating_already_stored')
        );
    }
    
    /**
     * Method getQuestions
     *
     * @param int    $id   [explicite description]
     * @param string $type [explicite description]
     *
     * @return collection
     */
    public function getQuestions(int $id, string $type)
    {
        $type = ($type == 'review') ? ProductQuestion::QUESTION_TYPE_BY_REVIEW : ProductQuestion::QUESTION_TYPE_BY_PRODUCT;
        $result = $this->productQuestionRepository->getQuestions($id, $type);
        $productsQues = ProductQuestionResource::collection(
            $result
        );
        
        if ($productsQues->isEmpty()) {
            return $this->successResponse(
                __('message.error.product.no_questions_found')
            );
        }
        
        return $productsQues;
    }
    
    /**
     * Method removeScannedProduct
     *
     * @param int $id [explicite description] 
     *
     * @return void
     */
    public function removeScannedProduct(int $id)
    {
        $result = $this->productScannerRepository
            ->deleteScannedProduct($id, auth()->user()->id);
        if ($result) {
            return $this->successResponse(
                __('message.success.scan_product_removed')
            );
        }
        return $this->errorResponse(
            __('message.error.something_went_wrong')
        );
    }
        
    /**
     * Method scanProduct
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function scanProduct(Request $request)
    {
        $post = $request->all();
        $post['user_id'] = auth()->user()->id;
        $product = $this->productScannerRepository->storeScanProduct($post);
        if ($product) {
            return $this->successResponse(
                __('message.success.store_scan_product'),
                ['udi_number' => $product->udi_number]
            );
        }
        return $this->errorResponse(
            __('message.error.something_went_wrong')
        );
    }
    /**
     * Method storeProductQuestionAnswers
     *
     * @param StoreProductQuestionAnwswerRequest $request [explicite description]
     *
     * @return void
     */
    public function storeProductQuestionAnswers(StoreProductQuestionAnwswerRequest $request)
    {
        $post = $request->all();
        $answer = $this->productQuestionAnswerRepository->saveProductAnswer($post);
        if (!$answer) {
            return $this->errorResponse(
                __('message.error.product_answers_already_stored')
            );
        }
        if ($answer) {
            return $this->successResponse(__('message.success.answer_stored_success'));
        }
        return $this->errorResponse(
            __('message.error.something_went_wrong')
        );
    }
}

