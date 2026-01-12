<?php
namespace App\Repositories\Interfaces;

use App\Models\User;

Interface UserRepositoryInterface
{  
    /**
     * Method stateList
     *
     * @param id $id 
     *
     * @return array
     */
    public function getUser(int $id);
    
    /**
     * Method createUser
     *
     * @param array $data 
     * @param bool  $doVerify [explicite description]
     *
     * @return void
     */
    public function createUser(array $data, bool $doVerify = false);
    
    /**
     * Method updateUser
     *
     * @param array $data 
     * @param int   $id 
     * @param ?User $user [explicite description]
     *
     * @return void
     */
    public function updateUser(array $data, int $id, ?User $user = null);
    
    /**
     * Method sendOtp
     *
     * @param User $user [explicite description]
     *
     * @return void
     */
    public function sendOtp(User $user);
    
    /**
     * Method checkLogin
     *
     * @param array $data [explicite description]
     *
     * @return void
     */
    public function checkLogin(array $data);
    
    /**
     * Method verifyOtp
     *
     * @param array $data [explicite description]
     *
     * @return void
     */
    public function verifyOtp(array $data);
    
    /**
     * Method updatePassword
     *
     * @param $data $data [explicite description]
     *
     * @return void
     */
    public function updatePassword($data);
    
    /**
     * Method getUserList
     *
     * @param array $data 
     * @param $paginate $paginate 
     *
     * @return void
     */
    public function getUserList(array $data, $paginate = true);    
    /**
     * Method changeStatus
     *
     * @param array $data 
     * @param int   $id 
     *
     * @return void
     */
    public function changeStatus(array $data, int $id);
    
    /**
     * Method getUserDetail
     *
     * @param $id $id [explicite description]
     *
     * @return void
     */
    public function getUserDetail($id);
    
    /**
     * Method deleteUser
     *
     * @param User $user [explicite description]
     *
     * @return void
     */
    public function deleteUser(User $user);
}
