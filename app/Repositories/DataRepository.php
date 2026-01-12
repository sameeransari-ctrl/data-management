<?php

namespace App\Repositories;

use Exception;
use Carbon\Carbon;
use App\Models\Data;
use Illuminate\Support\Facades\DB;
use App\Exceptions\CustomException;

class DataRepository extends BaseRepository
{
    protected $model;

    /**
     * Method __construct
     *
     * @param Data $model [explicite description]
     *
     * @return void
     */
    public function __construct(Data $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * Method getDataList
     *
     * @param array $data     [explicite description]
     * @param $paginate $paginate [explicite description]
     *
     * @return object
     */
    // public function getDataList(array $data, $paginate = true): object
    // {
    //     $sortFields = [
    //         'id' => 'datas.id',
    //         'name' => 'datas.company_name',
    //         'created_at' => 'datas.created_at',
    //     ];
    //     $export = $data['export'] ?? '';
    //     $search = $data['search'] ?? '';
    //     $name = $data['company_name'] ?? '';
    //     $status = $data['status'] ?? '';
    //     // $email = $data['email'] ?? '';
    //     // $type = $data['type'] ?? '';
    //     $fromDate = $data["fromDate"] ?? '';
    //     $toDate = $data["toDate"] ?? '';
    //     $designationIds = $data['designation'] ?? []; 
    //     $companyWebsiteIds = $data['company_websites'] ?? [];
    //     $offset = $data['start'] ?? '';
    //     $sortDirection = $data['sortDirection'] ?? 'desc';
    //     $sort = $sortFields['id'];
    //     if (array_key_exists('sortColumn', $data) && isset($sortFields[$data['sortColumn']])) {
    //         $sort = $sortFields[$data['sortColumn']];
    //     }

    //     if (isset($export) && $export == 'export') {
    //         $paginate = false;
    //     }
    //     $limit = $data['size'] ?? config('constants.pagination_limit.defaultPagination');
    //     $this->model->offset($offset);
    //     $this->model->limit($limit);
    //     $companyData = $this->model
    //         ->with('companyWebsite')
    //         ->when(
    //             $search,
    //             function ($q) use ($search) {
    //                 $q->where(
    //                     function ($q) use ($search) {
    //                         $q->where('company_name', 'like', '%' . $search . '%')
    //                             // ->orWhere('company_website', 'like', '%' . $search . '%')
    //                             ->orWhere('company_industries', 'like', '%' . $search . '%')
    //                             ->orWhere('first_name', 'like', '%' . $search . '%')
    //                             ->orWhere('last_name', 'like', '%' . $search . '%')
    //                             ->orWhere('email', 'like', '%' . $search . '%');
    //                             // ->orWhere('email', 'like', '%' . $search . '%');
    //                     }
    //                 );
    //             }
    //         )
    //         ->when(
    //             $fromDate,
    //             function ($q) use ($fromDate) {
    //                 $q->where('created_at', '>=', $fromDate . ' 00:00:00');
    //             }
    //         )->when(
    //             $toDate,
    //             function ($q) use ($toDate) {
    //                 $q->where('created_at', '<=', $toDate . ' 23:59:59');
    //             }
    //         )
    //         ->when(
    //             $name,
    //             function ($q) use ($name) {
    //                 $q->where('company_name', 'like', '%' . $name . '%');
    //             }
    //         )
    //         ->when(
    //             $status,
    //             function ($q) use ($status) {
    //                 $q->where('status', $status);
    //             }
    //         )
    //         // ->when(
    //         //     $email,
    //         //     function ($q) use ($email) {
    //         //         $q->where('email', 'like', '%' . $email . '%');
    //         //     }
    //         // )
    //          ->when(!empty($designationIds), function ($q) use ($designationIds) {
    //                 $q->whereHas('designations', function ($q2) use ($designationIds) {
    //                     $q2->whereIn('designation_id', $designationIds);
    //                 });
    //             })
    //          ->when(!empty($companyWebsiteIds), function ($q) use ($companyWebsiteIds) {
    //             $q->whereIn('company_website_id', $companyWebsiteIds);
    //         })
    //         ->orderBy($sort, $sortDirection);
    //     if (!$paginate) {
    //         $result = $companyData->orderBy($sort, $sortDirection)->get();
    //     } else {
    //         $result = $companyData->orderBy($sort, $sortDirection)->paginate($limit);
    //     }
    //     return $result;
    // }


    public function getDataList(array $data, $paginate = true): object
    {
        $sortFields = [
            'id' => 'datas.id',
            'name' => 'datas.company_name',
            'created_at' => 'datas.created_at',
        ];
        $export = $data['export'] ?? '';
        $search = $data['search'] ?? '';
        $name = $data['company_name'] ?? '';
        $status = $data['status'] ?? '';
        // $email = $data['email'] ?? '';
        // $type = $data['type'] ?? '';
        $fromDate = $data["fromDate"] ?? '';
        $toDate = $data["toDate"] ?? '';
        $designationTags = $data['designation'] ?? [];
        $companyWebsiteIds = $data['company_websites'] ?? [];
        $companyWebsitesBulk = $data['company_websites_bulk'] ?? [];
        $offset = $data['start'] ?? '';
        $sortDirection = $data['sortDirection'] ?? 'desc';
        $sort = $sortFields['id'];
        if (array_key_exists('sortColumn', $data) && isset($sortFields[$data['sortColumn']])) {
            $sort = $sortFields[$data['sortColumn']];
        }

        if (isset($export) && $export == 'export') {
            $paginate = false;
        }
        $limit = $data['size'] ?? config('constants.pagination_limit.defaultPagination');
        $this->model->offset($offset);
        $this->model->limit($limit);
        $companyData = $this->model
            ->with('companyWebsite')
            // ->when(
            //     $search,
            //     function ($q) use ($search) {
            //         $q->where(
            //             function ($q) use ($search) {
            //                 $q->where('company_name', 'like', '%' . $search . '%')
            //                     // ->orWhere('company_website', 'like', '%' . $search . '%')
            //                     ->orWhere('company_industries', 'like', '%' . $search . '%')
            //                     ->orWhere('first_name', 'like', '%' . $search . '%')
            //                     ->orWhere('last_name', 'like', '%' . $search . '%')
            //                     ->orWhere('email', 'like', '%' . $search . '%');
            //                     // ->orWhere('email', 'like', '%' . $search . '%');
            //             }
            //         );
            //     }
            // )
            ->when($search, function ($q) use ($search) {

                $search = trim(strtolower($search));

                $q->where(function ($qq) use ($search) {

                    // DOMAIN search → uses company_website index
                    if (str_contains($search, '.')) {
                        $qq->where('company_website', 'like', $search . '%');
                    }
                    // COMPANY NAME search → uses company_name index
                    else {
                        $qq->where('company_name', 'like', $search . '%');
                    }

                });
            })
            
            ->when(
                $fromDate,
                function ($q) use ($fromDate) {
                    $q->where('created_at', '>=', $fromDate . ' 00:00:00');
                }
            )->when(
                $toDate,
                function ($q) use ($toDate) {
                    $q->where('created_at', '<=', $toDate . ' 23:59:59');
                }
            )
            ->when(
                $name,
                function ($q) use ($name) {
                    $q->where('company_name', 'like', '%' . $name . '%');
                }
            )
            ->when(
                $status,
                function ($q) use ($status) {
                    $q->where('status', $status);
                }
            )
            // ->when(
            //     $email,
            //     function ($q) use ($email) {
            //         $q->where('email', 'like', '%' . $email . '%');
            //     }
            // )
            //  ->when(!empty($designationTags), function ($q) use ($designationTags) {
            //         $q->where(function ($q2) use ($designationTags) {
            //             foreach ($designationTags as $tag) {
            //                 $tag = trim(strtolower($tag));
            //                 if ($tag === '') continue;
            //                 $q2->orWhereRaw('LOWER(designation_search) LIKE ?', ["%{$tag}%"]);
            //             }
            //         });
            //     })
            ->when(!empty($designationTags), function ($q) use ($designationTags) {

                    $normalizedTags = collect($designationTags)
                        ->map(fn ($tag) => normalizeTag($tag))
                        ->filter()
                        ->unique()
                        ->values()
                        ->all();

                    if (empty($normalizedTags)) {
                        return;
                    }

                    $q->whereHas('designations', function ($dq) use ($normalizedTags) {
                        $dq->whereIn('designations.normalized', $normalizedTags);
                    });
                })
             ->when(!empty($companyWebsiteIds), function ($q) use ($companyWebsiteIds) {
                $q->whereIn('company_website_id', $companyWebsiteIds);
            })
            ->when(!empty($companyWebsitesBulk), function ($q) use ($companyWebsitesBulk) {
                $q->whereIn('company_website', array_map('strtolower', $companyWebsitesBulk)); // match websites from textarea
            })
            ->orderBy($sort, $sortDirection);
        if (!$paginate) {
            $result = $companyData->orderBy($sort, $sortDirection)->get();
        } else {
            $result = $companyData->orderBy($sort, $sortDirection)->paginate($limit);
        }
        return $result;
    }

    /**
     * Method getData
     *
     * @param int $id [explicite description]
     *
     * @return Data
     */
    public function getData(int $id): ?Data
    {
        return $this->model->find($id);
    }

    /**
     * Method changeStatus
     *
     * @param array $data [explicite description]
     * @param int   $id   [explicite description]
     *
     * @return void
     */
    public function changeStatus(array $data, int $id)
    {
        $datas = $this->getData($id);
        if (!empty($datas)) {
            return $this->model->where('id', $id)->update(
                [
                    'status' => $data['status'],
                ]
            );
        }
    }

    public function getTotalActiveDatas()
    {
        return $this->model->where('status', Data::STATUS_ACTIVE)
            ->get();
    }

    /**
     * Method getTotalInactiveUsers
     *
     * @return void
     */
    public function getTotalInactiveDatas()
    {
        return $this->model->where('status', Data::STATUS_INACTIVE)
            ->get();
    }
    
}
