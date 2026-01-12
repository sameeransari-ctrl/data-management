<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\ExportFsn;
use App\Http\Controllers\Controller;
use App\Http\Resources\{fieldSafetyNoticeResource,};
use Illuminate\Http\Request;
use App\Repositories\{FieldSefetyNoticeRepository};
use Maatwebsite\Excel\Facades\Excel;

class FsnController extends Controller
{
    protected $fieldSefetyNoticeRepository;

    /**
     * Method __construct
     *
     * @param FieldSefetyNoticeRepository $fieldSefetyNoticeRepository
     *
     * @return void
     */
    public function __construct(FieldSefetyNoticeRepository $fieldSefetyNoticeRepository)
    {
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

        if ($request->ajax()) {
            return fieldSafetyNoticeResource::collection(
                $this->fieldSefetyNoticeRepository->getList($data)
            );
        }
        return view('admin.fsn.index');
    }
    
    /**
     * Method fsnViewDetails
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function fsnViewDetails(Request $request)
    {
        $fsnResult = $request->all();
        $fromTimeZone = config('app.timezone');
        return view('admin.fsn._fsnView', compact('fsnResult', 'fromTimeZone'));
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
        try {
            $params = $request->all();
            $exportFsn = new ExportFsn($params, $this->fieldSefetyNoticeRepository);
            $fileName = 'fsn' . date('YmdHis') . '.csv';
            return Excel::download(
                $exportFsn,
                $fileName
            );
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage());
        }
    }
}
