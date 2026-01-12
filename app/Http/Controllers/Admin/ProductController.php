<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\CustomException;
use App\Exports\Admin\ExportProducts;
use Exception;
use App\Models\{Product, User, ProductQuestion};
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;

use App\Http\Requests\Admin\{
    AddProductRequest,
    EditProductRequest,
    AddQuestionRequest,
    EditQuestionRequest,
    ImportProductRequest
};
use App\Http\Resources\BasicUdiResource;
use App\Imports\ProductImport;
use App\Repositories\{
    ProductRepository,
    UserRepository,
    ProductFileRepository,
    ProductQuestionRepository,
    BasicUdidRepository,
    ClientRoleRepository,
    RatingReviewRepository
};
use Maatwebsite\Excel\Facades\Excel;
use mysqli;
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
     * @param ProductRepository         $productRepository
     * @param UserRepository            $userRepository
     * @param ProductFileRepository     $productFileRepository
     * @param ProductQuestionRepository $productQuestionRepository
     * @param BasicUdidRepository       $basicUdidRepository
     * @param ClientRoleRepository      $clientRoleRepository
     * @param RatingReviewRepository    $ratingReviewRepository
     *
     * @return void
     */
    public function __construct(
        ProductRepository $productRepository,
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
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        try {
            $data = $request->all();
            $product = "";
            if ($request->ajax()) {
                $product = ProductResource::collection(
                    $this->productRepository->getProductList($data)
                );
                return $product;
            }
            $productClassList = $this->productRepository->getAllProductClass();
            $verificationList = Product::$productVerificationTypes;
            return view('admin.product.index', compact('product', 'productClassList', 'verificationList'));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
        return view('admin.product.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        $productClassList = $this->productRepository->getAllProductClass();
        $clientList = $this->userRepository->getUserTypeByUserList(User::TYPE_CLIENT);
        $basicUdids = $this->basicUdidRepository->basicUdidList();
        return view(
            'admin.product.add',
            compact('productClassList', 'clientList', 'basicUdids')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AddProductRequest $request
     *
     * @return void
     */
    public function store(AddProductRequest $request)
    {
        $params = $request->all();
        $params['added_by'] = auth()->user()->id;
        $product = $this->productRepository->saveProduct($params);
        throw_if(
            empty($product),
            CustomException::class,
            __('message.error.something_went_wrong'),
            400
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
            ),
            ['productId' => $product->id]
        );
    }

    /**
     * Method show
     *
     * @param Request $request
     * @param int     $id
     *
     * @return void
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
        $product = $this->productRepository->firstWhere(['id' => $id]);
        if (empty($product)) {
            return redirect()->route('admin.product.index');
        }
        $basicUdiData = $this->basicUdidRepository->basicUdidName($product->basic_udid_id);
        $productClass = $this->productRepository->getProductClass($product->class_id);
        $getclientData = $this->userRepository->getUserDetail($product->client_id);
        $role = $this->clientRoleRepository->getRole($getclientData->client_role_id);
        $productRatingData =  $product->productRatingReview;
        $avgRatingData = $this->ratingReviewRepository->getRatingAverage($product->id);
        $avgRating = round($avgRatingData, 1);
        return view(
            'admin.product.product-details',
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
     * Show the form for editing the specified resource.
     *
     * @param string $id
     *
     * @return void
     */
    public function edit(string $id)
    {
        $productQuestionTypeReview = $this->productQuestionRepository
            ->getAllProductQuestion($id, ProductQuestion::QUESTION_TYPE_BY_REVIEW);
        $productQuestionTypeProduct = $this->productQuestionRepository
            ->getAllProductQuestion($id, ProductQuestion::QUESTION_TYPE_BY_PRODUCT);
        $productFiles = $this->productFileRepository->getAllProductFiles($id);
        /* product question listing end */
        $product = $this->productRepository->firstWhere(['id' => $id]);
        $clientId = $product->client_id;
        $basicUdids = $this->basicUdidRepository->getAllWhere(['added_by' => $clientId]);
        $productClassList = $this->productRepository->getAllProductClass();
        $clientList = $this->userRepository->getUserTypeByUserList(User::TYPE_CLIENT);
        return view(
            'admin.product.edit',
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
     * Update the specified resource in storage.
     *
     * @param EditProductRequest $request
     * @param string             $id
     *
     * @return void
     */
    public function update(EditProductRequest $request, string $id)
    {
        $params = $request->except(['_token', '_method']);
        $params['updated_by'] = auth()->user()->id;
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
                $html = view('admin.product.question_list', compact('productQuestionList'))->render();
            }
            $productQuestionList = $this->productQuestionRepository
                ->getAllProductQuestion($id, ProductQuestion::QUESTION_TYPE_BY_PRODUCT);
            if (!empty($productQuestionList)) {
                $html1 = view('admin.product.question_list', compact('productQuestionList'))->render();
            }
            return $this->successResponse(
                __(
                    'message.success.updated',
                    [
                        'type' => __('labels.product')
                    ]
                ),
                ['productId' => $id, 'html' => $html, 'html1' => $html1]
            );
        }
        return $this->errorResponse(
            __('message.error.something_went_wrong')
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     *
     * @return void
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Method addQuestion
     *
     * @param string $type
     *
     * @return void
     */
    public function addQuestion(string $type)
    {
        try {
            $html = view('admin.product.modals.add_question', compact('type'))->render();
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
                __('message.error.something_went_wrong'),
                400
            );

            /* product question listing start */
            $productQuestionList = $this->productQuestionRepository->getSingleProductQuestion($productQuestion->id);
            $html = view('admin.product.question_list', compact('productQuestionList'))->render();
            /* product question listing end */

            $questionType = $request->input('question_type');

            return $this->successResponse(
                __(
                    'message.success.added',
                    [
                        'type' => __('labels.questionnaire_for_review')
                    ]
                ),
                ['html' => $html, 'questionType' => $questionType]
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Method editQuestion
     *
     * @param $id $id
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
                __('message.error.something_went_wrong'),
                400
            );

            $html = view('admin.product.modals.edit_question', compact('productQuestion'))->render();
            return $this->successResponse(null, $html);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Method updateQuestion
     *
     * @param EditQuestionRequest $request
     * @param int                 $id
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
                __('message.error.something_went_wrong'),
                400
            );
            $data = $request->all();
            $data['id'] = $id;

            $productQuestionData = $this->productQuestionRepository->saveProductQuestion($data);

            $productQuestionList = $this->productQuestionRepository->getSingleProductQuestion($productQuestionData->id);
            $html = view('admin.product.question_list', compact('productQuestionList'))->render();
            /* product question listing end */

            $questionType = $productQuestion[0]['question_type'];

            return $this->successResponse(
                __(
                    'message.success.updated',
                    [
                        'type' => __('labels.questionnaire_for_review')
                    ]
                ),
                ['html' => $html, 'questionType' => $questionType, 'questionId' => $id]
            );
        } catch (Exception $e) {
            info('updateQuestion', ['e' => $e]);
            return $this->errorResponse($e->getMessage());
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
                __('message.error.something_went_wrong'),
                400
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
            $html = view('admin.product.question_list', compact('productQuestionList'))->render();
            return $this->successResponse(
                __(
                    'message.success.product_questions',
                    [
                        'type' => __('labels.product')
                    ]
                ),
                ['productId' => $id, 'html' => $html, 'questionType' => $questionType]
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
     * Method exportCsv
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function exportCsv(Request $request)
    {
        $params = $request->all();
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
        return view('admin.product.modals._import');
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

    /**
     * Method getRatingDetails
     *
     * @param $productId $productId [explicite description]
     *
     * @return void
     */
    public function getRatingDetails($productId)
    {
        $ratingReviewData = $this->ratingReviewRepository->getRatingReviews($productId);
        $fromTimeZone = config('app.timezone');
        return view('admin.product.rating-details', compact('ratingReviewData', 'fromTimeZone'));
    }

    /**
     * Method getClientBasicUdids
     *
     * @param Request $request
     * @param int     $clientId
     *
     * @return void
     */
    public function getClientBasicUdids(Request $request, $clientId)
    {
        try {
            $basicUdIds = $this->basicUdidRepository->getAllWhere(['added_by' => $clientId]);
            return BasicUdiResource::collection($basicUdIds);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage());
        }
    }
}
