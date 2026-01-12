<?php

namespace App\Http\Controllers\Client;

use App\Exports\Client\ExportFsns;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\FsnRequest;
use App\Http\Resources\fieldSafetyNoticeResource;
use App\Repositories\FieldSefetyNoticeRepository;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class FsnController extends Controller
{
    protected $productRepository;
    protected $fsnRepository;

    /**
     * Method __construct
     *
     * @param ProductRepository           $productRepository [explicite description]
     * @param FieldSefetyNoticeRepository $fsnRepository     [explicite description]
     *
     * @return void
     */
    public function __construct(ProductRepository $productRepository, FieldSefetyNoticeRepository $fsnRepository)
    {
        $this->productRepository = $productRepository;
        $this->fsnRepository = $fsnRepository;
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
        if ($request->ajax()) {
            $data['current_client'] = true;
            return fieldSafetyNoticeResource::collection(
                $this->fsnRepository->getList($data)
            );
        }
        return view('client.fsn.index');
    }

    /**
     * Method create
     *
     * @return void
     */
    public function create()
    {
        $products = $this->productRepository->getWhereProductList(['client_id' => auth()->user()->id]);
        return view('client.fsn._addEditPreview', compact('products'));
    }

    /**
     * Method store
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function store(FsnRequest $request)
    {
        $params = $request->except(['_token', '_method']);

        $params['notice_description'] = $params['description'];
        $params['attachment_type'] = $params['attachType'];
        $params['client_id'] = auth()->user()->id;
        
        $result = $this->fsnRepository->createFsn($params);
        if (!empty($result)) {
            return $this->successResponse(
                __(
                    'message.success.added',
                    [
                        'type' => __('labels.fsn_notice')
                    ]
                )
            );
        }
    }

    /**
     * Method show
     *
     * @param Request $request [explicite description]
     * @param $id      $id [explicite description]
     *
     * @return void
     */
    public function show(Request $request, $id)
    {
        $fsnResult = $request->all();
        return view('client.fsn._show', compact('fsnResult'));
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
     * @param string  $id      [explicite description]
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
     * Method getSingleProduct
     *
     * @param \App\Http\Controllers\Client\ int $id $id — [explicite description]
     *
     * @return void
     */
    public function getSingleProduct($id)
    {
        return $this->productRepository->getProduct($id);
    }

    /**
     * Method previewModal
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function previewModal(FsnRequest $request)
    {
        $udiNumber = $this->productRepository->getProduct($request->product_id)->udi_number;
        return view('client.fsn._preview', compact('request', 'udiNumber'));
    }

    /**
     * Method editFsn
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function editFsn(Request $request)
    {
        $products = $this->productRepository->getWhereProductList(['client_id' => auth()->user()->id]);
        return view('client.fsn._addEdit', compact('products', 'request'));
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
        $exportFsn = new ExportFsns($params, $this->fsnRepository);
        $fileName = 'fsn' . date('YmdHis') . '.csv';
        return Excel::download(
            $exportFsn,
            $fileName
        );
    }

}
