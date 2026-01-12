<?php

namespace App\Repositories;

use App\Models\BasicUdid;
use App\Models\User;
use App\Notifications\CreateBasicUdi;
use Illuminate\Support\Facades\Auth;

class BasicUdidRepository extends BaseRepository
{
    protected $model;

    /**
     * Method __construct
     *
     * @param BasicUdid $model [explicite description]
     *
     * @return void
     */
    public function __construct(BasicUdid $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * Method basicUdidList
     *
     * @param array $data [explicite description]
     *
     * @return void
     */
    public function basicUdidList(array $data = [])
    {
        return $this->model->orderBy('name', 'asc')->get();
    }

    /**
     * Method basicUdidName
     *
     * @param $id $id [explicite description]
     *
     * @return void
     */
    public function basicUdidName($id)
    {
        return $this->model->where('id', $id)->first();
    }

    /**
     * Method createBasicUdi
     *
     * @param array $data [explicite description]
     *
     * @return BasicUdid
     */
    public function createBasicUdi(array $data): BasicUdid
    {
        $basicUdi = $this->create($data);
        $userType = Auth::user()->user_type;
        if (($userType == User::TYPE_STAFF) && $userType) {
            $user = User::onlyAdmin()->first();
            $user->notify(new CreateBasicUdi());
        }
        return $basicUdi;
    }

    /**
     * Method getBasicUdiList
     *
     * @param array $data     [explicite description]
     * @param $paginate $paginate [explicite description]
     *
     * @return object
     */
    public function getBasicUdiList(array $data, $paginate = true): object
    {
        $sortFields = [
            'id' => 'id',
            'name' => 'name',
        ];
        $export = $data['export'] ?? '';
        $search = $data['search'] ?? '';
        $name = $data['name'] ?? '';
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
        $user = $this->model
            ->when(
                $search,
                function ($q) use ($search) {
                    $q->whereHas(
                        'client',
                        function ($q) use ($search) {
                            $q->where('users.name', 'like', '%' . $search . '%')
                                ->orwhere('users.actor_id', 'like', '%' . $search . '%')
                                ->orWhere('basic_udids.name', 'like', '%' . $search . '%');
                        }
                    );
                }
            )
            ->when(
                $name,
                function ($q) use ($name) {
                    $q->where('name', 'like', '%'.$name.'%');
                }
            )
            ->when(
                (!empty($data['current_staff'])),
                function ($q) {
                    $q->where('added_by', auth()->user()->id);
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
     * Method updateBasicUdi
     *
     * @param array      $data     [explicite description]
     * @param int        $id       [explicite description]
     * @param ?BasicUdid $basicudi [explicite description]
     *
     * @return BasicUdid
     */
    public function updateBasicUdi(array $data, int $id, ?BasicUdid $basicudi = null): BasicUdid
    {
        if (! $basicudi) {
            $basicudi = $this->getBasicUdi($id);
        }

        $updated = $this->update($data, $id);
        if ($updated) {
            return $this->getBasicUdi($basicudi->id);
        }

        return $basicudi;
    }

    /**
     * Method getBasicUdi
     *
     * @param int $id [explicite description]
     *
     * @return BasicUdid
     */
    public function getBasicUdi(int $id): ?BasicUdid
    {
        return $this->model->find($id);
    }

    /**
     * Method getWhereBasicUdiList
     *
     * @param array $where [explicite description]
     *
     * @return void
     */
    public function getWhereBasicUdiList(array $where)
    {
        return $this->model->where($where)->get();
    }

}
