<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\ExportClients;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\{ClientRequest};
use App\Http\Resources\Collections\CityResource;
use App\Http\Resources\{fieldSafetyNoticeResource, UserResource};
use Illuminate\Http\Request;
use App\Models\{User};
use App\Repositories\{CityRepository, CountryRepository, ClientRepository, ClientRoleRepository, UserCardRepository, ProductRepository, FieldSefetyNoticeRepository};
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ClientController extends Controller
{

    protected $clientRepository;
    protected $countryRepository;
    protected $cityRepository;
    protected $clientRoleRepository;
    protected $userCardRepository;
    protected $productRepository;
    protected $fieldSefetyNoticeRepository;

    /**
     * Method __construct
     *
     * @return void
     */
    public function __construct(
        ClientRepository $clientRepository,
        CountryRepository $countryRepository,
        CityRepository $cityRepository,
        ClientRoleRepository $clientRoleRepository,
        UserCardRepository $userCardRepository,
        ProductRepository $productRepository,
        FieldSefetyNoticeRepository $fieldSefetyNoticeRepository
    ) {
        $this->clientRepository = $clientRepository;
        $this->countryRepository = $countryRepository;
        $this->cityRepository = $cityRepository;
        $this->clientRoleRepository = $clientRoleRepository;
        $this->userCardRepository = $userCardRepository;
        $this->productRepository = $productRepository;
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
        $roles = $this->clientRoleRepository->getAll();
        $statusList = User::$statusList;
        if ($request->ajax()) {
            return UserResource::collection(
                $this->clientRepository->getClientList($data)
            );
        }
        return view('admin.client.index', compact('roles', 'statusList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $client = null;
        $roles = $this->clientRoleRepository->getAll();
        $countries = $this->countryRepository->getAll();
        return view('admin.client.addEdit', compact('client', 'countries', 'roles'));
    }


    /**
     * Method store
     *
     * @param ClientRequest $request [explicite description]
     *
     * @return void
     */
    public function store(ClientRequest $request)
    {
        try {
            $params = $request->except(['_token', '_method']);
            $params['status'] = User::STATUS_ACTIVE;
            $params['user_type'] = User::TYPE_CLIENT;
            $params['email_verified_at'] = Carbon::now();
            $params['phone_number_verified_at'] = Carbon::now();
            $result = $this->clientRepository->createClient($params, false);

            if (!empty($result)) {
                return $this->successResponse(
                    __(
                        'message.success.added',
                        [
                            'type' => __('labels.client')
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
     * @param Request $request [explicite description]
     * @param int     $id      [explicite description]
     *
     * @return void
     */
    public function show(Request $request, $id)
    {
        $data = $request->all();
        $client = $this->clientRepository->getClient($id);
        $userCard = $this->userCardRepository->getUserCard($id);
        $role = $this->clientRoleRepository->getRole($client->client_role_id);
        $productCount = $this->productRepository->getProductCount($id);
        $fsnCount = $this->fieldSefetyNoticeRepository->getTotalFsnCount($id);


        if (empty($client)) {
            return redirect()->route('admin.client.index');
        }

        if ($request->ajax()) {
            $data['client_id'] = $id;
            return fieldSafetyNoticeResource::collection(
                $this->fieldSefetyNoticeRepository->getList($data)
            );
        }
        return view('admin.client.client-details', compact('client', 'userCard', 'role', 'productCount', 'fsnCount'));
    }

    /**
     * Method edit
     *
     * @param int $id [explicite description]
     *
     * @return void
     */
    public function edit($id)
    {
        $roles = $this->clientRoleRepository->getAll();
        $countries = $this->countryRepository->getAll();
        $client = $this->clientRepository->getClient($id);
        $userCard = $this->userCardRepository->getUserCard($id);
        $cities = $this->cityRepository->cityList(['country_id' => $client->country_id]);
        return view('admin.client.addEdit', compact('client', 'countries', 'cities', 'roles', 'userCard'));
    }



    /**
     * Method update
     *
     * @param ClientRequest $request [explicite description]
     * @param int           $id      [explicite description]
     *
     * @return void
     */
    public function update(ClientRequest $request, $id)
    {
        try {
            $params = $request->except(['_token', '_method']);
            $result = $this->clientRepository->updateClient($params, $id);
            if (!empty($result)) {
                return $this->successResponse(
                    __(
                        'message.success.updated',
                        [
                            'type' => __('labels.client')
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
        $client = $this->clientRepository->changeStatus($params, $params['id']);
        return $this->successResponse(trans('message.success.'.$params['status']));
    }


    /**
     * Method getCityByCountryId
     *
     * @param Request $request [explicite description]
     * @param int     $id      [explicite description]
     *
     * @return void
     */
    public function getCityByCountryId(Request $request, int $id)
    {
        try {
            $cityList = $this->cityRepository->cityList(['country_id' => $id]);
            return CityResource::collection($cityList);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage());
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
        try {
            $params = $request->all();
            $exportClient = new ExportClients($params, $this->clientRepository);
            $fileName = 'clients' . date('YmdHis') . '.csv';
            return Excel::download(
                $exportClient,
                $fileName
            );
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage());
        }
    }
    
    /**
     * Method getFieldSafetyNotices
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function getFieldSafetyNotices(Request $request)
    {
        $fsnResult = $request->all();
        return view('admin.client._fsnView', compact('fsnResult'));
    }

}
