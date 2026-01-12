<?php

namespace App\Repositories;

use App\Models\Notification;
use Exception;
use Illuminate\Support\Facades\Auth;

/**
 * NotificationRepository
 */
class NotificationRepository extends BaseRepository
{
    protected $model;

    /**
     * Method __construct
     *
     *
     * @return void
     */
    public function __construct(Notification $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * Admin getNotification
     *
     * @param $data     array
     * @param $paginate mixed
     */
    public function getNotification(array $data, $paginate = true): object
    {
        $sortFields = [
            'id' => 'id',
        ];
        $fromDate = $data['fromDate'] ?? '';
        $toDate = $data['toDate'] ?? '';
        $search = $data['search'] ?? '';
        $start = $data['start'] ?? '';
        $sortDirection = $data['sortDirection'] ?? 'desc';
        $sort = $sortFields['id'];
        $sortColumn = $data['sortColumn'] ?? '';

        $offset = $start;
        $limit = $data['size'] ?? config(
            'constants.pagination_limit.defaultPagination'
        );

        $notification = $this->model
            ->where('notifiable_id', Auth::user()->id)
            ->when(
                $search,
                function ($q) use ($search) {
                    $q->where('data', 'like', '%'.$search.'%');
                }
            )->when(
                $fromDate,
                function ($q) use ($fromDate) {
                    $q->where('created_at', '>=', $fromDate.' 00:00:00');
                }
            )->when(
                $toDate,
                function ($q) use ($toDate) {
                    $q->where('created_at', '<=', $toDate. ' 23:59:59');
                }
            )
            ->when(
                $sortColumn,
                function ($q) use ($sortColumn, $sortDirection) {
                    $q->orderBy($sortColumn, $sortDirection);
                }
            )
            ->when(
                $offset,
                function ($q) use ($offset) {
                    $q->offset($offset);
                }
            )
            ->when(
                $limit,
                function ($q) use ($limit) {
                    $q->limit($limit);
                }
            );
        if (! $paginate) {
            $result = $notification->orderBy($sort, $sortDirection)->get();
        } else {
            $result = $notification->orderBy($sort, $sortDirection)->paginate($limit);
        }
        return $result;
    }

    /**
     * Method markAsRead
     *
     * @return void
     */
    public function markAsRead()
    {
        try {
            $user = Auth::user();
            return $user->unreadNotifications->markAsRead();
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Method markAsReadById
     *
     * @param $id integer
     * @return bool
     */
    public function markAsReadById($id)
    {
        try {
            $userId = Auth::user()->id;

            return $this->update(
                [['user_id', $userId], ['id', $id]],
                ['is_readed' => 1]
            );
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
