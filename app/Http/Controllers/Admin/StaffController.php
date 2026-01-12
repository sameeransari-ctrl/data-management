<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Exports\Admin\{ExportStaffs};
use App\Http\Controllers\Controller;
use App\Http\Resources\{UserResource};
use App\Repositories\{RoleRepository, StaffRepository, CountryRepository};
use App\Http\Requests\Admin\{StaffRequest};
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use Carbon\Carbon;

class StaffController extends Controller
{
    protected $staffRepository;
    protected $countryRepository;
    protected $roleRepository;
    
    /**
     * Method __construct
     *
     * @return void
     */
    public function __construct(
        StaffRepository $staffRepository,
        CountryRepository $countryRepository,
        RoleRepository $roleRepository
    ) {
        $this->staffRepository = $staffRepository;
        $this->countryRepository = $countryRepository;
        $this->roleRepository = $roleRepository;
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
        $roles = $this->roleRepository->allRoleList();
        if ($request->ajax()) {
            $data['not_current_staff'] = true;
            return UserResource::collection(
                $this->staffRepository->getStaff($data)
            );
        }
        return view('admin.staff.index', compact('roles'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = $this->countryRepository->getAll();
        $roles = $this->roleRepository->roleList();
        $html = view('admin.staff._addEdit', compact('countries', 'roles'))->render();

        return $this->successResponse('', $html);
    }


    /**
     * Method store
     *
     * @param StaffRequest $request [explicite description]
     *
     * @return void
     */
    public function store(StaffRequest $request)
    {
        try {
            $params = $request->all();
            $roleInactivated = $this->roleRepository->getInActiveRoleById($request->user_type);
            if (!empty($roleInactivated)) {
                return $this->errorResponse(
                    __('message.error.this_role_is_inactive')
                );
            }
            $params['email_verified_at'] = Carbon::now();
            $params['phone_number_verified_at'] = Carbon::now();
            $result = $this->staffRepository->createStaff($params);

            if (!empty($result)) {
                return $this->successResponse(
                    __(
                        'message.success.added',
                        [
                            'type' => __('labels.staff')
                        ]
                    )
                );
            }
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
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
     * @param $id $id [explicite description]
     *
     * @return void
     */
    public function edit($id)
    {
        $countries = $this->countryRepository->getAll();
        $staff = $this->staffRepository->getStaffDetail($id);
        $roleId = "";
        if (count($staff->roles) > 0) {
            $roleId = $staff->roles->pluck('id')->toArray()[0];
        }
        $roles = $this->roleRepository->roleList();
        $html = view('admin.staff._addEdit', compact('staff', 'countries', 'roles', 'roleId'))->render();
        return $this->successResponse('', $html);
    }


    /**
     * Method update
     *
     * @param StaffRequest $request [explicite description]
     * @param $id      [explicite description]
     *
     * @return void
     */
    public function update(StaffRequest $request, $id)
    {
        try {
            $params = $request->all();
            $roleInactivated = $this->roleRepository->getInActiveRoleById($request->user_type);
            if (!empty($roleInactivated)) {
                return $this->errorResponse(
                    __('message.error.this_role_is_inactive')
                );
            }
            $result = $this->staffRepository->updateStaff($params, $id);
            if (!empty($result)) {
                return $this->successResponse(
                    __(
                        'message.success.updated',
                        [
                            'type' => __('labels.staff')
                        ]
                    )
                );
            }
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }


    /**
     * Method destroy
     *
     * @param $id $id [explicite description]
     *
     * @return void
     */
    public function destroy($id)
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
        $staff = $this->staffRepository->changeStatus($params, $params['id']);
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
            $exportUser = new ExportStaffs($params, $this->staffRepository);
            $fileName = 'staffs' . date('YmdHis') . '.csv';
            return Excel::download(
                $exportUser,
                $fileName
            );
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage());
        }
    }

}
