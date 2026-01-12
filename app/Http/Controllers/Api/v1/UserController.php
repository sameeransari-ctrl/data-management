<?php

namespace App\Http\Controllers\Api\v1;

use App\Exceptions\CustomException;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use App\Repositories\UserActivityRepository;
use App\Http\Requests\Api\UpdateUserRequest;
use App\Http\Requests\Api\ChangePasswordRequest;
use App\Http\Requests\Api\CheckEmailOrPhoneNumberRequest;
use App\Http\Requests\Api\UserActivityRequest;

class UserController extends Controller
{
    
    protected $userRepository;
    protected $userActivityRepository;
    
    /**
     * Method __construct
     *
     * @param UserRepository $userRepository 
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository, UserActivityRepository $userActivityRepository)
    {
        $this->userRepository = $userRepository;
        $this->userActivityRepository = $userActivityRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
     * Display the specified resource.
     *
     * @param User $user 
     * 
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $loggedUserId = auth()->user()->id;
        if (empty($user)) {
            return $this->errorResponse(
                __('message.error.user_detail_not_found')
            );
        }
        $userId = $user->id;
        if ($loggedUserId != $userId) {
            return $this->errorResponse(
                __('message.error.something_went_wrong')
            );
        }

        return $this->successResponse(
            __('user.userProfile'),
            new UserResource($user)
        );
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request 
     * @param User              $user 
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $loggedUserId = auth()->user()->id;
        if (empty($user)) {
            return $this->errorResponse(
                __('message.error.user_detail_not_found')
            );
        }
        $userId = $user->id;
        if ($loggedUserId != $userId) {
            return $this->errorResponse(
                __('message.error.something_went_wrong')
            );
        }

        $post = $request->all();
        $updatedUser = $this->userRepository->updateUser($post, $userId, $user);
        if ($updatedUser) {
            return $this->successResponse(
                __('user.userProfileUpdated'),
                new UserResource($updatedUser)
            );
        } 
        return $this->errorResponse(
            __('message.error.something_went_wrong')
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user 
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->id !== auth()->user()->id) {
            throw new CustomException(__('message.error.exception.not_allowed'), 403);
        }
        $this->userRepository->deleteUser($user);
        return $this->deleteResponse(
            __('message.success.deleted')
        );
    }

    /** 
     *  Method update email.
     * 
     * @param \Illuminate\Http\Request $request 
     * 
     * @return \Illuminate\Http\JsonResponse
     */  
    public function changePassword(ChangePasswordRequest $request)  
    {  
        $user = auth()->user();
        $post = $request->only('password');
        $this->userRepository->update($post, $user->id);
        return $this->successResponse(
            __('message.success.password-change')
        );
    }
    
    /**
     * Method checkEmailOrPhoneNumber
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function checkEmailOrPhoneNumber(CheckEmailOrPhoneNumberRequest $request)
    {
        return $this->successResponse(__('message.success.available'));
    }
    
    /**
     * Method storeActivity
     *
     * @param UserActivityRequest $request [explicite description]
     *
     * @return void
     */
    public function storeActivity(UserActivityRequest $request)
    {
        $user = auth()->user();
        $post = $request->only('activity_time');
        $this->userActivityRepository->userActivityUpdateOrCreate($post, $user->id);
        return $this->successResponse(
            __('message.success.user_activity_updated')
        );
    }
}
