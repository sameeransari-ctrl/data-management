<?php
namespace App\Repositories\Interfaces;

/**
 * Interface StateRepositoryInterface
 */
Interface StateRepositoryInterface
{  
    /**
     * Method stateList
     *
     * @param array $where [explicite description]
     *
     * @return array
     */
    public function stateList(array $where);
}
