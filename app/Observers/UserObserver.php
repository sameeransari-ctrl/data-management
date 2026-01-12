<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{    
    /**
     * Method created
     *
     * @param User $user [explicite description]
     *
     * @return void
     */
    public function created(User $user): void
    {
        //
    }
    
    /**
     * Method updated
     *
     * @param User $user [explicite description]
     *
     * @return void
     */
    public function updated(User $user): void
    {
        //
    }
    
    /**
     * Method deleted
     *
     * @param User $user [explicite description]
     *
     * @return void
     */
    public function deleted(User $user): void
    {
        /* $user->userDevice->delete();
        $user->productScanners->delete();
        $user->productReview->delete();
        $user->productAnswer->delete(); */
    }
    
    /**
     * Method restored
     *
     * @param User $user [explicite description]
     *
     * @return void
     */
    public function restored(User $user): void
    {
        //
    }
    
    /**
     * Method forceDeleted
     *
     * @param User $user [explicite description]
     *
     * @return void
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
