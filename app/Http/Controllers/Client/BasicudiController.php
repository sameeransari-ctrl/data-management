<?php

namespace App\Http\Controllers\Client;

use App\Exports\Client\ExportBasicUdi;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\BasicUdiRequest;
use App\Http\Requests\Client\ImportBasicUdiRequest;
use App\Http\Resources\BasicUdiResource;
use App\Imports\BasicUdiImport;
use App\Models\BasicUdid;
use App\Repositories\BasicUdidRepository;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;

class BasicudiController extends Controller
{
    protected $basicUdidRepository;
    /**
     * Method __construct
     *
     * @param BasicUdidRepository $basicUdidRepository [explicite description]
     *
     * @return void
     */
    public function __construct(BasicUdidRepository $basicUdidRepository)
    {
        $this->basicUdidRepository = $basicUdidRepository;
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
            $data['current_staff'] = true;
            return BasicUdiResource::collection(
                $this->basicUdidRepository->getBasicUdiList($data)
            );
        }
        return view('client.basicudi.index');
    }

    /**
     * Method create
     *
     * @return void
     */
    public function create()
    {
        $basicudi = null;
        return view('client.basicudi._addEdit', compact('basicudi'));
    }

    /**
     * Method store
     *
     * @param BasicUdiRequest $request [explicite description]
     *
     * @return void
     */
    public function store(BasicUdiRequest $request)
    {
        $params = $request->except(['_token', '_method']);
        $params['added_by'] = auth()->user()->id;
        $result = $this->basicUdidRepository->createBasicUdi($params);
        if (!empty($result)) {
            return $this->successResponse(
                __(
                    'message.success.added',
                    [
                        'type' => __('labels.basic_udi')
                    ]
                )
            );
        }
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
        //
    }

    /**
     * Method edit
     *
     * @param BasicUdid $basicudi [explicite description]
     *
     * @return void
     */
    public function edit(BasicUdid $basicudi)
    {
        return view('client.basicudi._addEdit', compact('basicudi'));
    }

    /**
     * Method update
     *
     * @param BasicUdiRequest $request [explicite description]
     * @param int             $id      $id [explicite description]
     *
     * @return void
     */
    public function update(BasicUdiRequest $request, $id)
    {
        $params = $request->except(['_token', '_method']);
        $result = $this->basicUdidRepository->updateBasicUdi($params, $id);
        if (!empty($result)) {
            return $this->successResponse(
                __(
                    'message.success.updated',
                    [
                        'type' => __('labels.basic_udi')
                    ]
                )
            );
        }
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
     * Method exportCsv
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function exportCsv(Request $request)
    {
        $params = $request->all();
        $params['current_staff'] = true;
        $exportUdi = new ExportBasicUdi($params, $this->basicUdidRepository);
        $fileName = 'basicudi' . date('YmdHis') . '.csv';
        return Excel::download(
            $exportUdi,
            $fileName
        );
    }

    /**
     * Method importBasicUdiForm
     *
     * @return void
     */
    public function importBasicUdiForm()
    {
        return view('client.basicudi.modals._import');
    }

    /**
     * Method importBasicUdi
     *
     * @param ImportBasicUdiRequest $request [explicite description]
     *
     * @return void
     */
    public function importBasicUdi(ImportBasicUdiRequest $request)
    {
        $file = $request->file('basic_udi');
        $import = new BasicUdiImport();
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
            $stat = import_failed($file, $failures, 'importBasicUdi');
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
