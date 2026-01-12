<?php

namespace App\Http\Controllers\Client;

use App\Exports\Client\ExportUsers;
use App\Http\Controllers\Controller;
use App\Http\Resources\ScannedProductResource;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    protected $userRepository;

    /**
     * Method __construct
     *
     * @param UserRepository $userRepository [explicite description]
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
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
            return UserResource::collection(
                $this->userRepository->getMyScannedProductUserList($data)
            );
        }
        return view('client.user.index');
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
     * @param Request $request [explicite description]
     * @param $id      $id [explicite description]
     *
     * @return void
     */
    public function show(Request $request, $id)
    {
        $data = $request->all();
        $user = $this->userRepository->getUserDetail($id);
        if (empty($user)) {
            return redirect()->route('client.user.index');
        }

        if ($request->ajax()) {
            return ScannedProductResource::collection(
                $this->userRepository->getUserProductDetail($data, $id)
            );
        }
        return view('client.user.user-details', compact('user'));
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
        $exportUser = new ExportUsers($params, $this->userRepository);
        $fileName = 'users' . date('YmdHis') . '.csv';
        return Excel::download(
            $exportUser,
            $fileName
        );
    }
}
