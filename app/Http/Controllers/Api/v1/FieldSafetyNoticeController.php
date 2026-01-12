<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\fieldSafetyNoticeResource;
use Illuminate\Http\Request;
use App\Repositories\ProductRepository;
use App\Repositories\ProductScannerRepository;
use App\Repositories\FieldSefetyNoticeRepository;

class FieldSafetyNoticeController extends Controller
{

    protected $productRepository;
    protected $productScannerRepository;
    protected $fieldSefetyNoticeRepository;
                
    /**
     * Method __construct
     *
     * @return void
     */
    public function __construct(
        ProductRepository $productRepository,
        ProductScannerRepository $productScannerRepository,
        FieldSefetyNoticeRepository $fieldSefetyNoticeRepository
    ) {
        $this->productRepository = $productRepository;
        $this->productScannerRepository = $productScannerRepository;
        $this->fieldSefetyNoticeRepository = $fieldSefetyNoticeRepository;
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
        $fsnList = fieldSafetyNoticeResource::collection(
            $this->fieldSefetyNoticeRepository->getFsnList($data)
        );

        return $fsnList;
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
     * @param string $id [explicite description]
     *
     * @return void
     */
    public function show(string $id)
    {
        $where = ["id" => $id];
        $fsn = $this->fieldSefetyNoticeRepository->firstWhere($where);
        if (!empty($fsn)) {
            return $this->successResponse(
                __('message.fsnDetails'),
                new fieldSafetyNoticeResource($fsn)
            );
        } else {
            return $this->errorResponse(
                __('message.error.fsn.not_found')
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
}
