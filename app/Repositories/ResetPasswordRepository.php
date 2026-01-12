<?php

namespace App\Repositories;

use App\Exceptions\CustomException;
use App\Jobs\ForgotPasswordMailJob;
use App\Models\PasswordReset;
use App\Models\User;

class ResetPasswordRepository extends BaseRepository
{
    protected $userRepository;

    protected $passwordResetModel;

    /**
     * Class constructor
     */
    public function __construct(UserRepository $userRepository, PasswordReset $passwordResetModel)
    {
        parent::__construct($passwordResetModel);
        $this->userRepository = $userRepository;
        $this->passwordResetModel = $passwordResetModel;
    }

    /**
     * Create specified resource
     *
     * @param  array  $data
     * @return bool
     */
    public function createRecord($data)
    {
        $this->passwordResetModel->create($data);
        $data['reset_password_link'] = (!in_array($data['user_type'], [User::TYPE_ADMIN, User::TYPE_CLIENT])) ? route(
            'user.reset-password-form',
            $data['token']
        ) : route($data['url'], $data['token']);

        dispatch(new ForgotPasswordMailJob($data));

        return true;
    }

    /**
     * Delete specified resource by condition
     *
     * @param  array  $data
     * @return bool
     */
    public function deleteRecord($data)
    {
        return $this->passwordResetModel->where($data)->delete();
    }

    /**
     * Method resetPassword
     *
     * @param $data $data
     * @return bool
     */
    public function resetPassword($data)
    {
        $user = $this->userRepository->firstWhere(['email' => $data['email']]);
        if (empty($user)) {
            throw new CustomException(__('message.error.exception.user_not_found'));
        }

        $tokenExists = $this->passwordResetModel
            ->where('email', $data['email'])
            ->where('token', $data['token'])
            ->first();

        if (empty($tokenExists)) {
            throw new CustomException(__('message.error.link_expire'));
        }

        $this->userRepository->update(
            [
                'password' => $data['password'],
            ],
            $user->id
        );

        return $this->deleteRecord(['email' => $data['email']]);
    }
}
