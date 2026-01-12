<?php

namespace App\Repositories;
use App\Models\Product;
use App\Models\FieldSafetyNotice;
use App\Models\User;
use App\Notifications\CreateFsn;
use App\Notifications\FirebaseNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

class FieldSefetyNoticeRepository extends BaseRepository
{
    protected $model;



    /**
     * Method __construct
     *
     * @param FieldSafetyNotice $model [explicite description]
     *
     * @return void
     */
    public function __construct(FieldSafetyNotice $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * Method getList
     *
     * @param array $data     [explicite description]
     * @param $paginate $paginate [explicite description]
     *
     * @return object
     */
    public function getList(array $data, $paginate = true): object
    {
        $sortFields = [
            'id' => 'id',
            'title' => 'title',
            'udi_number' => 'products.udi_number',
            'product_name' => 'products.product_name',
            'attachment_type' => 'attachment_type',
            'created_at' => 'created_at',
        ];
        $search = $data['search'] ?? '';
        $offset = $data['start'] ?? '';
        $clientId = $data['client_id'] ?? '';
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
        $fsnData = $this->model
            ->when(
                $search,
                function ($q) use ($search) {
                    $q->whereHas(
                        'product',
                        function ($q) use ($search) {
                            $q->where('title', 'like', '%'.$search.'%')
                                ->orWhere('products.product_name', 'like', '%'.$search.'%')
                                ->orWhere('products.udi_number', 'like', '%'.$search.'%');
                        }
                    );
                }
            )
            ->when(
                (!empty($clientId)),
                function ($q) use ($clientId) {
                    $q->where('client_id', '=', $clientId);
                }
            )
            ->when(
                (!empty($data['current_client'])),
                function ($q) {
                    $q->where('client_id', '=', auth()->user()->id);
                }
            )
            ->orderBy($sort, $sortDirection);
        if (!$paginate) {
            $result = $fsnData->orderBy($sort, $sortDirection)->get();
        } else {
            $result = $fsnData->orderBy($sort, $sortDirection)->paginate($limit);
        }
        return $result;
    }

    /**
     * Method getFsnList
     *
     * @param array $data [explicite description]
     * @param $paginate $paginate [explicite description]
     *
     * @return object
     */
    public function getFsnList(array $data, $paginate = true): object
    {
        $sortFields = [
            'id' => 'id',
            'product_name' => 'product_name',
            'status' => 'status',
            'created_at' => 'created_at'
        ];

        $search = $data['search'] ?? '';
        $sendDate = $data['send_date'] ?? '';
        $productId = $data['product_id'] ?? '';
        $offset = $data['start'] ?? '';
        $sortDirection = $data['sortDirection'] ?? 'desc';
        $sort = $sortFields['created_at'];
        $fsnType = $data['type'] ?? '';

        if (array_key_exists('sortColumn', $data) && isset($sortFields[$data['sortColumn']])) {
            $sort = $sortFields[$data['sortColumn']];
        }

        $limit = $data['size'] ?? config('constants.pagination_limit.defaultPagination');
        $this->model->offset($offset);
        $this->model->limit($limit);
        $userId = $data['userId'];

        $responsData = $this->model
            ->select('field_safety_notices.*', 'products.product_name');
        if ($fsnType == 'my') {
            $responsData->leftJoin('product_scanners', 'product_scanners.product_id', 'field_safety_notices.product_id');
        }
        $responsData->leftJoin('products', 'products.id', 'field_safety_notices.product_id');

        $responsData->when(
            $search,
            function ($q) use ($search) {
                $q->where(
                    function ($query) use ($search) {
                        $query->where('field_safety_notices.title', 'like', '%'.$search.'%')
                            ->orWhere('products.udi_number', 'like', '%'.$search.'%');
                    }
                );
            }
        );

        $responsData->when(
            $productId,
            function ($q) use ($productId) {
                $q->where('field_safety_notices.product_id', $productId);
            }
        );

        $responsData->when(
            $sendDate,
            function ($q) use ($sendDate) {
                $q->whereDate('field_safety_notices.created_at', $sendDate);
            }
        );

        $responsData->when(
            ($fsnType == 'my'),
            function ($q) use ($userId) {
                $q->where('product_scanners.user_id', $userId);
                $q->whereNull('product_scanners.deleted_at');
            }
        );

        $responsData->when(
            ($fsnType == 'other'),
            function ($q) use ($userId) {
                $q->whereNotIn(
                    'field_safety_notices.product_id', function ($query) use ($userId) {
                        $query->select('product_id')
                            ->from('product_scanners')
                            ->where('user_id', $userId);
                    }
                );
            }
        );

        if (! $paginate) {
            $results = $responsData->orderBy($sort, $sortDirection)->get();
        } else {
            $results = $responsData->orderBy($sort, $sortDirection)->paginate($limit);
        }
        return $results;
    }

    /**
     * Method createFsn
     *
     * @param array $data [explicite description]
     *
     * @return void
     */
    public function createFsn(array $data)
    {
        if (!empty($data['xlsx_file']) && $data['attachType'] == 'xlsx') {
            $data['upload_file'] = uploadFile($data['xlsx_file'], config('constants.file.fsn.path'));
        } else if (!empty($data['video_file']) && $data['attachType'] == 'video') {
            $data['upload_file'] = uploadFile($data['video_file'], config('constants.file.fsn.path'), '', ['width'=> 600, 'height' => 450]);
            $ext = pathinfo($data['upload_file'], PATHINFO_EXTENSION);
            $thumbImageName = basename($data['upload_file'], '.'.$ext);
            $data['thumbnail'] = "fsn_files/thumb/".$thumbImageName.".jpg";
        } else if (!empty($data['url']) && $data['attachType'] == 'url') {
            $data['upload_file'] = $data['url'];
        }
        $fsn = $this->create($data);
        if ($fsn) {
            $product = Product::select('product_name', 'udi_number')->where('id', $data['product_id'])->first();
            $productName = (!empty($product)) ? ucwords($product->product_name.'/'.$product->udi_number) : '';
            $userDevicesAaray = $this->getTotalScannedUsersDeviceIdByProductId($data['product_id']);
            $title = trans('message.notification.fsnCreate.title', ['productName' => $productName]);
            $message = trans('message.notification.fsnCreate.message', ['productName' => $productName]);
            $additionalData = ["fsn_id" => $fsn->id];

            $userData = User::onlyAdmin()->first();
            $userData->notify(new CreateFsn($product));
            $payLoad = [
                'title' => $title,
                'message' => $message,
                'additionalData' => $additionalData
            ];
            if (count($userDevicesAaray) > 0 && count($userDevicesAaray) <= 999) {
                $payLoad['userDevicesAaray'] = $userDevicesAaray;
                Notification::send(null, new FirebaseNotification($payLoad));
            } else {
                $userDevices = array_chunk($userDevicesAaray, 999);
                if (!empty($userDevices)) {
                    for ($i=0;$i<count($userDevices);$i++) {
                        $payLoad['userDevicesAaray'] = $userDevices[$i];
                        Notification::send(null, new FirebaseNotification($payLoad));
                    }
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Method getFsnDetail
     *
     * @param $id $id [explicite description]
     *
     * @return void
     */
    public function getFsnDetail($id)
    {
        return $this->model->find($id);
    }


    /**
     * Method getTotalFsnCount
     *
     * @param $clientId="" $clientId [explicite description]
     *
     * @return void
     */
    public function getTotalFsnCount($clientId="")
    {
        if ($clientId != "") {
            return $this->model->where('client_id', $clientId)->get()->count();
        }
        return $this->model->get()->count();
    }

    /**
     * Method getTotalScannedUsersDeviceIdByProductId
     *
     * @param $productId $productId [explicite description]
     *
     * @return array
     */
    public function getTotalScannedUsersDeviceIdByProductId($productId)
    {
        return $this->model
            ->leftJoin('product_scanners', 'product_scanners.product_id', 'field_safety_notices.product_id')
            ->leftJoin('users', 'users.id', 'product_scanners.user_id')
            ->leftJoin('user_devices', 'user_devices.user_id', 'users.id')
            ->where('field_safety_notices.product_id', $productId)
            ->where('users.notification_alert', 1)
            ->whereNotNull('user_devices.device_id')
            ->select('user_devices.device_id')
            ->groupBy('user_devices.device_id')
            ->distinct()
            ->get()
            ->pluck('device_id')
            ->toArray();
    }
}
