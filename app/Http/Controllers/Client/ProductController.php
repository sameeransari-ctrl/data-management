<?php

namespace App\Http\Controllers\Client;

use App\Exceptions\CustomException;
use App\Exports\Client\ExportProducts;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\AddProductRequest;
use App\Http\Requests\Client\AddQuestionRequest;
use App\Http\Requests\Client\EditProductRequest;
use App\Http\Requests\Client\EditQuestionRequest;
use App\Http\Requests\Client\ImportProductRequest;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Imports\ProductImport;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\{Product, User, ProductQuestion};
use App\Repositories\{ProductRepository,
        UserRepository,
        ProductFileRepository,
        ProductQuestionRepository,
        BasicUdidRepository,
        ClientRoleRepository,
        RatingReviewRepository
    };
use Maatwebsite\Excel\HeadingRowImport;

class ProductController extends Controller
{
    protected $productRepository;
    protected $userRepository;
    protected $productFileRepository;
    protected $productQuestionRepository;
    protected $basicUdidRepository;
    protected $clientRoleRepository;
    protected $ratingReviewRepository;

    /**
     * Method __construct
     *
     * @param ProductRepository         $productRepository         [explicite description]
     * @param UserRepository            $userRepository            [explicite description]
     * @param ProductFileRepository     $productFileRepository     [explicite description]
     * @param ProductQuestionRepository $productQuestionRepository [explicite description]
     * @param BasicUdidRepository       $basicUdidRepository       [explicite description]
     * @param ClientRoleRepository      $clientRoleRepository      [explicite description]
     * @param RatingReviewRepository    $ratingReviewRepository    [explicite description]
     *
     * @return void
     */
    public function __construct(ProductRepository $productRepository,
        UserRepository $userRepository,
        ProductFileRepository $productFileRepository,
        ProductQuestionRepository $productQuestionRepository,
        BasicUdidRepository $basicUdidRepository,
        ClientRoleRepository $clientRoleRepository,
        RatingReviewRepository $ratingReviewRepository
    ) {
        $this->productRepository = $productRepository;
        $this->userRepository = $userRepository;
        $this->productFileRepository = $productFileRepository;
        $this->productQuestionRepository = $productQuestionRepository;
        $this->basicUdidRepository = $basicUdidRepository;
        $this->clientRoleRepository = $clientRoleRepository;
        $this->ratingReviewRepository = $ratingReviewRepository;
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
        try {
            $data = $request->all();
            $product = "";
            if ($request->ajax()) {
                $data['current_client'] = true;
                $product = ProductResource::collection(
                    $this->productRepository->getProductList($data)
                );
                return $product;
            }
            $productClassList = $this->productRepository->getAllProductClass();
            $verificationList = Product::$productVerificationTypes;
            return view('client.product.index', compact('product', 'productClassList', 'verificationList'));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
        return view('client.product.index');
    }

    /**
     * Method create
     *
     * @return void
     */
    public function create()
    {
        $productClassList = $this->productRepository->getAllProductClass();
        $basicUdids = $this->basicUdidRepository->getWhereBasicUdiList(['added_by' => auth()->user()->id]);
        return view('client.product.add', compact('productClassList', 'basicUdids'));
    }

    /**
     * Method store
     *
     * @param AddProductRequest $request [explicite description]
     *
     * @return void
     */
    public function store(AddProductRequest $request)
    {
        $params = $request->all();
        $params['added_by'] = auth()->user()->id;
        $params['client_id'] = auth()->user()->id;
        $params['verification_by'] = Product::VERIFICATION_EUDAMED;
        $product = $this->productRepository->saveProduct($params);
        throw_if(
            empty($product),
            CustomException::class,
            __('message.error.something_went_wrong'), 400
        );

        $fileUrl = $request->input('file_url');
        if (!empty($fileUrl) && count($fileUrl) > 0 && $fileUrl[0] != '') {
            $this->productFileRepository->saveProductFile($fileUrl, $product);
        }

        return $this->successResponse(
            __(
                'message.success.added',
                [
                    'type' => __('labels.product')
                ]
            ), ['productId' => $product->id]
        );
    }

    /**
     * Method show
     *
     * @param Request                           $request [explicite description]
     * @param \App\Http\Controllers\Client\ int $id      $id — [explicite description]
     *
     * @return mixed
     */
    public function show(Request $request, $id)
    {
        $productQuestionTypeReview = $this->productQuestionRepository->getAllProductQuestion(
            $id,
            ProductQuestion::QUESTION_TYPE_BY_REVIEW
        );
        $productQuestionTypeProduct = $this->productQuestionRepository->getAllProductQuestion(
            $id,
            ProductQuestion::QUESTION_TYPE_BY_PRODUCT
        );
        $productFiles = $this->productFileRepository->getAllProductFiles($id);
        $product = $this->productRepository->firstWhere(['id' => $id, 'client_id' => auth()->user()->id]);
        if (empty($product)) {
            return redirect()->route('client.product.index');
        }
        $basicUdiData = $this->basicUdidRepository->basicUdidName($product->basic_udid_id);
        $productClass = $this->productRepository->getProductClass($product->class_id);
        $getclientData = $this->userRepository->getUserDetail($product->client_id);
        $role = $this->clientRoleRepository->getRole($getclientData->client_role_id);
        $productRatingData =  $product->productRatingReview;
        $avgRatingData = $this->ratingReviewRepository->getRatingAverage($product->id);
        $avgRating = round($avgRatingData, 1);
        return view(
            'client.product.product-details',
            compact(
                'product',
                'productFiles',
                'productQuestionTypeReview',
                'productQuestionTypeProduct',
                'productClass',
                'getclientData',
                'id',
                'basicUdiData',
                'role',
                'avgRating'
            )
        );
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
        $productQuestionTypeReview = $this->productQuestionRepository->getAllProductQuestion(
            $id,
            ProductQuestion::QUESTION_TYPE_BY_REVIEW
        );
        $productQuestionTypeProduct = $this->productQuestionRepository->getAllProductQuestion(
            $id,
            ProductQuestion::QUESTION_TYPE_BY_PRODUCT
        );
        $productFiles = $this->productFileRepository->getAllProductFiles($id);
        /* product question listing end */
        $product = $this->productRepository->firstWhere(['id' => $id, 'client_id' => auth()->user()->id]);
        if (empty($product)) {
            return redirect()->route('client.product.index');
        }
        $basicUdids = $this->basicUdidRepository->getWhereBasicUdiList(['added_by' => auth()->user()->id]);
        $productClassList = $this->productRepository->getAllProductClass();
        $clientList = $this->userRepository->getUserTypeByUserList(User::TYPE_CLIENT);
        return view(
            'client.product.edit',
            compact(
                'product',
                'productFiles',
                'productQuestionTypeReview',
                'productQuestionTypeProduct',
                'productClassList',
                'clientList',
                'id',
                'basicUdids'
            )
        );
    }

    /**
     * Method update
     *
     * @param EditProductRequest $request [explicite description]
     * @param string             $id      [explicite description]
     *
     * @return void
     */
    public function update(EditProductRequest $request, string $id)
    {
        $params = $request->except(['_token', '_method']);
        $params['updated_by'] = auth()->user()->id;
        $params['client_id'] = auth()->user()->id;
        $product = $this->productRepository->updateProduct($params);
        if (!empty($product)) {
            $fileUrl = $request->input('file_url');
            if (!empty($fileUrl) && count($fileUrl) > 0 && $fileUrl[0] != '') {
                $this->productFileRepository->saveProductFile($fileUrl, $product);
            }
            $html = "";
            $html1 = "";
            $productQuestionList = $this->productQuestionRepository
                ->getAllProductQuestion($id, ProductQuestion::QUESTION_TYPE_BY_REVIEW);
            if (!empty($productQuestionList)) {
                $html = view('client.product.question_list', compact('productQuestionList'))->render();
            }

            $productQuestionList = $this->productQuestionRepository
                ->getAllProductQuestion($id, ProductQuestion::QUESTION_TYPE_BY_PRODUCT);
            if (!empty($productQuestionList)) {
                $html1 = view('client.product.question_list', compact('productQuestionList'))->render();
            }

            return $this->successResponse(
                __(
                    'message.success.updated',
                    [
                        'type' => __('labels.product')
                    ]
                ), ['productId' => $id, 'html' => $html, 'html1' => $html1]
            );
        }
        return $this->errorResponse(
            __('message.error.something_went_wrong')
        );
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
     * Method getRatingDetails
     *
     * @param int $productId
     *
     * @return void
     */
    public function getRatingDetails(int $productId)
    {
        $ratingReviewData = $this->ratingReviewRepository->getRatingReviews($productId);
        return view('client.product.rating-details', compact('ratingReviewData'));
    }

    /**
     * Method addQuestion
     *
     * @param string $type [explicite description]
     *
     * @return void
     */
    public function addQuestion(string $type)
    {
        try {
            $html = view('client.product.modals.add_question', compact('type'))->render();
            return $this->successResponse('', $html);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Method storeQuestion
     *
     * @param AddQuestionRequest $request [explicite description]
     *
     * @return void
     */
    public function storeQuestion(AddQuestionRequest $request)
    {
        try {
            $params = $request->all();
            $productQuestion = $this->productQuestionRepository->saveProductQuestion($params);
            throw_if(
                empty($productQuestion),
                CustomException::class,
                __('message.error.something_went_wrong'), 400
            );

            /* product question listing start */
            $productQuestionList = $this->productQuestionRepository->getSingleProductQuestion($productQuestion->id);
            $html = view('client.product.question_list', compact('productQuestionList'))->render();
            /* product question listing end */

            $questionType = $request->input('question_type');

            return $this->successResponse(
                __(
                    'message.success.added',
                    [
                        'type' => __('labels.questionnaire_for_review')
                    ]
                ), ['html' => $html, 'questionType' => $questionType]
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Method editQuestion
     *
     * @param $id $id [explicite description]
     *
     * @return void
     */
    public function editQuestion($id)
    {
        try {
            $productQuestion = $this->productQuestionRepository->getSingleProductQuestion($id);
            throw_if(
                empty($productQuestion),
                CustomException::class,
                __('message.error.something_went_wrong'), 400
            );

            $html = view('client.product.modals.edit_question', compact('productQuestion'))->render();
            return $this->successResponse(null, $html);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }


    /**
     * Method updateQuestion
     *
     * @param EditQuestionRequest $request [explicite description]
     * @param $id      $id [explicite description]
     *
     * @return void
     */
    public function updateQuestion(EditQuestionRequest $request, $id)
    {
        try {
            $productQuestion = $this->productQuestionRepository->getSingleProductQuestion($id);
            throw_if(
                empty($productQuestion),
                CustomException::class,
                __('message.error.something_went_wrong'), 400
            );
            $data = $request->all();
            $data['id'] = $id;

            $productQuestionData = $this->productQuestionRepository->saveProductQuestion($data);
            $productQuestionList = $this->productQuestionRepository->getSingleProductQuestion($productQuestionData->id);
            $html = view('client.product.question_list', compact('productQuestionList'))->render();
            /* product question listing end */

            $questionType = $productQuestion[0]['question_type'];

            return $this->successResponse(
                __(
                    'message.success.updated',
                    [
                        'type' => __('labels.questionnaire_for_review')
                    ]
                ), ['html' => $html, 'questionType' => $questionType, 'questionId' => $id]
            );
        } catch (Exception $e) {
            info('updateQuestion', ['e' => $e]);
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Method getProductTypeQuestions
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function getProductTypeQuestions(Request $request)
    {
        $id = $request->product_id;
        $questionType = $request->question_type;
        $productQuestionList = $this->productQuestionRepository->getAllProductQuestion($id, $questionType);
        if (!empty($productQuestionList)) {
            $html = view('client.product.question_list', compact('productQuestionList'))->render();
            return $this->successResponse(
                __(
                    'message.success.product_questions',
                    [
                        'type' => __('labels.product')
                    ]
                ), ['productId' => $id, 'html' => $html, 'questionType' => $questionType]
            );
        }
        return '';
    }

    /**
     * Method updateProductStatus
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function updateProductStatus(Request $request)
    {
        $id = $request->product_id;
        $status = $request->status;
        $data = ['status' => $status];
        $product = $this->productRepository->update($data, $id);
        if ($product) {
            return $this->successResponse(
                __(
                    'message.success.product_updated'
                )
            );
        }
    }

    /**
     * Method destroyQuestion
     *
     * @param $id $id [explicite description]
     *
     * @return void
     */
    public function destroyQuestion($id)
    {
        try {
            $productQuestion = $this->productQuestionRepository->getSingleProductQuestion($id);
            throw_if(
                empty($productQuestion),
                CustomException::class,
                __('message.error.something_went_wrong'), 400
            );

            $this->productQuestionRepository->destroyQuestion($id);

            return $this->successResponse(
                __(
                    'message.success.deleted',
                    [
                        'type' => __('labels.question')
                    ]
                )
            );
        } catch (Exception $e) {
            info('deleteQuestion', ['e' => $e]);
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Method exportCsv
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function exportCsv(Request $request)
    {
        $params = $request->all();
        $params['current_client'] = true;
        $exportProduct = new ExportProducts($params, $this->productRepository);
        $fileName = 'products' . date('YmdHis') . '.csv';
        return Excel::download(
            $exportProduct,
            $fileName
        );
    }

    /**
     * Method importProductForm
     *
     * @return void
     */
    public function importProductForm()
    {
        return view('client.product.modals._import');
    }

    /**
     * Method importProduct
     *
     * @param ImportProductRequest $request [explicite description]
     *
     * @return void
     */
    public function importProduct(ImportProductRequest $request)
    {
        $file = $request->file('product_file');
        $import = new ProductImport();
        $headings = (new HeadingRowImport())->toArray($file);
        $missingField = array_diff($import->requiredHeading, $headings[0][0]);
        if (count($missingField) > 0) {
            return $this->errorResponse(
                __('message.error.excel.missing_required_headings', ['missingHeading' => implode(', ', $missingField)])
            );
        }

        $import->import($file);
        $failures = $import->failures();

        if ($failures->count() > 0) {
            $stat = import_failed($file, $failures, 'importReport');
            return [
                'error' => 400,
                'message' => __('message.success.partially_imported'),
                'stat' => $stat
            ];
        }

        return [
            'success' => 200,
            'message' => __('message.success.file_uploaded')
        ];
    }
}
