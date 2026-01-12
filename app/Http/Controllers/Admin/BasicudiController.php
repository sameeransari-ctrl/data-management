<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\ExportBasicUdi;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BasicUdiRequest;
use App\Http\Requests\Admin\ImportBasicUdiRequest;
use App\Http\Resources\BasicUdiResource;
use App\Imports\AdminBasicUdiImport;
use App\Models\BasicUdid;
use App\Models\User;
use App\Repositories\BasicUdidRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;

class BasicudiController extends Controller
{
    protected $basicUdidRepository;
    protected $userRepository;

    /**
     * Method __construct
     *
     * @param BasicUdidRepository $basicUdidRepository [explicite description]
     * @param UserRepository      $userRepository      [explicite description]
     *
     * @return void
     */
    public function __construct(BasicUdidRepository $basicUdidRepository, UserRepository $userRepository)
    {
        $this->basicUdidRepository = $basicUdidRepository;
        $this->userRepository = $userRepository;
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
            return BasicUdiResource::collection(
                $this->basicUdidRepository->getBasicUdiList($data)
            );
        }
        return view('admin.basicudi.index');
    }

    /**
     * Method create
     *
     * @return void
     */
    public function create()
    {
        $basicudi = null;
        $clientList = $this->userRepository->getUserTypeByUserList(User::TYPE_CLIENT);
        return view('admin.basicudi._addEdit', compact('basicudi', 'clientList'));
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
        $clientList = $this->userRepository->getUserTypeByUserList(User::TYPE_CLIENT);
        return view('admin.basicudi._addEdit', compact('basicudi', 'clientList'));
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
        return view('admin.basicudi.modals._import');
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
        $import = new AdminBasicUdiImport();
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
