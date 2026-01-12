<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\UserCard;
use App\Notifications\CreateClient;
use App\Notifications\WelcomeClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientRepository extends BaseRepository
{
    protected $model;
    
    /**
     * Method __construct
     *
     * @param User $model [explicite description]
     *
     * @return void
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }


    /**
     * Method getClient
     *
     * @param int $id [explicite description]
     *
     * @return User
     */
    public function getClient($id)
    {
        return $this->model->find($id);
    }

    /**
     * Method getUserByField
     *
     * @param $where $where
     *
     * @return object
     */
    public function getUserByField($where)
    {
        return $this->model->where($where)->first();
    }
    
    /**
     * Method createClient
     *
     * @param array $data 
     * @param bool  $doVerify 
     *
     * @return User
     */
    public function createClient(array $data, bool $doVerify = false): User
    {
        if (!empty($data['profile_image'])) {
            $data['profile_image'] = uploadFile(
                $data['profile_image'],
                config('constants.image.profile.path')
            );
        }
        $client = $this->create($data);
        if (!empty($client)) {
            $userType = Auth::user()->user_type;
            if (($userType == User::TYPE_STAFF)) {
                $user = User::onlyAdmin()->first();
                $user->notify(new CreateClient($client));
            }

            $userCard = new UserCard();
            $userCard->user_id = $client->id;
            $userCard->card_number = $data['card_number'];
            $userCard->card_holder_name = $data['card_holder_name'];
            $userCard->ifsc_code = $data['ifsc_code'];
            $userCard->iban_number = $data['iban_number'];
            $userCard->gtin_number = $data['gtin_number'];
            $userCard->save();

        }
        $client->notify(new WelcomeClient($client, $data['password']));
        return $client;

    }


    /**
     * Method updateClient
     *
     * @param array $data   [explicite description]
     * @param int   $id     [explicite description]
     * @param ?User $client [explicite description]
     *
     * @return User
     */
    public function updateClient(array $data, int $id, ?User $client = null): User
    {
        if (!$client) {
            $client = $this->getClient($id);
        }
        if (! empty($client) && ! empty($data['profile_image'])) {
            $data['profile_image'] = uploadFile(
                $data['profile_image'],
                config('constants.image.profile.path')
            );
            if (! empty($client->profile_image)) {
                deleteFile($client->profile_image);
            }
        } else {
            unset($data['profile_image']);
        }

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $updated = $this->update($data, $id);
        if ($updated) {
            $userCard =  UserCard::where('user_id', $id)->first();
            if (!empty($userCard)) {
                $userCard->user_id = $client->id;
                $userCard->card_number = $data['card_number'];
                $userCard->card_holder_name = $data['card_holder_name'];
                $userCard->ifsc_code = $data['ifsc_code'];
                $userCard->iban_number = $data['iban_number'];
                $userCard->gtin_number = $data['gtin_number'];
                $userCard->save();
            }
            return $this->getClient($client->id);
        }

        return $client;
    }


    /**
     * Method getClientList
     *
     * @param $data     [explicite description]
     * @param $paginate $paginate [explicite description]
     *
     * @return object
     */
    public function getClientList(array $data, $paginate = true): object
    {
        $sortFields = [
            'id' => 'users.id',
            'name' => 'users.name',
            'phone_number' => 'users.phone_number',
            'created_at' => 'users.created_at',
        ];
        $search = $data['search'] ?? '';
        $name = $data['name'] ?? '';
        $status = $data['status'] ?? '';
        $email = $data['email'] ?? '';
        $type = $data['type'] ?? '';
        $fromDate = $data["fromDate"]?? '';
        $toDate = $data["toDate"]?? '';
        $offset = $data['start'] ?? '';
        $sortDirection = $data['sortDirection'] ?? 'desc';
        $sort = $sortFields['id'];
        if (array_key_exists('sortColumn', $data) && isset($sortFields[$data['sortColumn']])) {
            $sort = $sortFields[$data['sortColumn']];
        }

        $limit = $data['size'] ?? config('constants.pagination_limit.defaultPagination');
        $this->model->offset($offset);
        $this->model->limit($limit);
        $user = $this->model
            ->with(['country', 'city', 'clientRole'])
            ->when(
                $search,
                function ($q) use ($search) {
                    $q->where(
                        function ($q) use ($search) {
                            $q->where('name', 'like', '%'.$search.'%')
                                ->orWhere('address', 'like', '%'.$search.'%')
                                ->orWhere('email', 'like', '%'.$search.'%')
                                ->orWhere(DB::raw("CONCAT(phone_code, phone_number)"), 'like', '%'.$search.'%');
                        }
                    );
                }
            )
            ->when(
                $fromDate,
                function ($q) use ($fromDate) {
                    $q->where('created_at', '>=', $fromDate. ' 00:00:00');
                }
            )->when(
                $toDate,
                function ($q) use ($toDate) {
                    $q->where('created_at', '<=', $toDate. ' 23:59:59');
                }
            )
            ->when(
                $name,
                function ($q) use ($name) {
                    $q->where('name', 'like', '%'.$name.'%');
                }
            )
            ->when(
                $status,
                function ($q) use ($status) {
                    $q->where('status', $status);
                }
            )
            ->when(
                $type,
                function ($q) use ($type) {
                    $q->where('client_role_id', $type);
                }
            )
            ->when(
                $email,
                function ($q) use ($email) {
                    $q->where('email', 'like', '%'.$email.'%');
                }
            )
            ->where(
                function ($q) {
                    $q->where('user_type', User::TYPE_CLIENT);
                }
            )
            ->orderBy($sort, $sortDirection);
        if (!$paginate) {
            $result = $user->orderBy($sort, $sortDirection)->get();
        } else {
            $result = $user->orderBy($sort, $sortDirection)->paginate($limit);
        }
        return $result;
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
        $client = $this->getClient($id);
        if (! empty($client)) {
            return $this->model->where('id', $id)->update(
                [
                    'status' => $data['status'],
                ]
            );
        }
    }
}
