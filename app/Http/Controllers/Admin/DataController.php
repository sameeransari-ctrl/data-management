<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\Admin\{ExportDatas};
use App\Http\Resources\{DataResource};
use App\Repositories\{DataRepository, DesignationRepository};
use App\Models\{User};
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\Admin\ImportDataRequest;
use App\Imports\AdminDataImport;
use Maatwebsite\Excel\HeadingRowImport;

class DataController extends Controller
{
    protected $dataRepository;
    protected $designationRepository;

    public function __construct(
        DataRepository $dataRepository,
        DesignationRepository $designationRepository,
    ) {
        $this->dataRepository = $dataRepository;
        $this->designationRepository = $designationRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $statusList = User::$statusList;
        if ($request->ajax()) {
            return DataResource::collection(
                $this->dataRepository->getDataList($data)
            );
        }
        return view('admin.data.index', compact('statusList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Method changeStatus
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function changeStatus(Request $request)
    {
        $params = $request->all();
        $data = $this->dataRepository->changeStatus($params, $params['id']);
        return $this->successResponse(trans('message.success.'.$params['status']));
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
            $exportData = new ExportDatas($params, $this->dataRepository);
            $fileName = 'datas' . date('YmdHis') . '.csv';
            return Excel::download(
                $exportData,
                $fileName
            );
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage());
        }
    }

    /**
     * Method importDataForm
     *
     * @return void
     */
    public function importDataForm()
    {
        return view('admin.data.modals._import');
    }

    /**
     * Method importData
     *
     * @param importDataRequest $request [explicite description]
     *
     * @return void
     */
    public function importData(ImportDataRequest $request)
    {
        $file = $request->file('datas');
        $import = new AdminDataImport();
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
            $stat = import_failed($file, $failures, 'importData');
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
