<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ChangePasswordRequest;
use App\Http\Requests\Admin\UpdateProfileRequest;
use App\Repositories\{UserRepository, CountryRepository};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Log;

class ProfileController extends Controller
{

    /**
     * AccountController constructor.
     *
     * @param UserRepository $userRepository
     * @param CountryRepository $countryRepository
     */
    public function __construct(
        protected UserRepository $userRepository,
        protected CountryRepository $countryRepository
    ) {
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countries = $this->countryRepository->getAll();
        $userData = $this->userRepository->getUser(Auth::user()->id);
        if (!empty($userData->date_of_birth)) {
            $userData->date_of_birth = getConvertedDate($userData->date_of_birth, 'd M, Y');
        }
        return view('admin.profile.index', compact('userData', 'countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
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
     * @param $id $id [explicite description]
     *
     * @return void
     */
    public function show($id)
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
        //
    }
    
    /**
     * Method update
     *
     * @param UpdateProfileRequest $request [explicite description]
     *
     * @return void
     */
    public function update(UpdateProfileRequest $request)
    {
        $params = $request->except(['_token', '_method', 'email', 'phone_code', 'phone_number']);
        $this->userRepository->updateUser($params, getLoggedInUserDetail()->id);
        return $this->successResponse(__('message.success.profile_updated'));

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
     * Remove the specified resource from storage.
     *
     * @param ChangePasswordRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $this->userRepository->updatePassword($request->all());
        return $this->successResponse(__('message.success.password-change'));

    }

    /**
     * Method changeUserPassword
     *
     * @return void
     */
    public function changeUserPassword()
    {
        $userData = $this->userRepository->getUser(Auth::user()->id);
        $fromTimeZone = config('app.timezone');
        return view('admin.profile.change-password', compact('userData', 'fromTimeZone'));
    }
}
