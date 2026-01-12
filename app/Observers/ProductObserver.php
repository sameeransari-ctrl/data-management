<?php

namespace App\Observers;

use App\Models\Product;

class ProductObserver
{ 

    /**
     * Method created
     *
     * @param Product $product [explicite description]
     *
     * @return void
     */
    public function created(Product $product): void
    {
        $udidNumber = $product->udi_number;
        $product->plain_udi_number = removeBracketsFromUdiNumber($udidNumber);
        $product->save();
    }
    
    /**
     * Method updated
     *
     * @param Product $product [explicite description]
     *
     * @return void
     */
    public function updated(Product $product): void
    {
        $udidNumber = $product->udi_number;
        $product->plain_udi_number = removeBracketsFromUdiNumber($udidNumber);
        $product->saveQuietly();
    }
    
    /**
     * Method deleted
     *
     * @param Product $product [explicite description]
     *
     * @return void
     */
    public function deleted(Product $product): void
    {
        //
    }
    
    /**
     * Method restored
     *
     * @param Product $product [explicite description]
     *
     * @return void
     */
    public function restored(Product $product): void
    {
        //
    }
    
    /**
     * Method forceDeleted
     *
     * @param Product $product [explicite description]
     *
     * @return void
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
