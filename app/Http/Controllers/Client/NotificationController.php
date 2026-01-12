<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\NotificationResource;
use App\Repositories\NotificationRepository;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $notificationRepository;
    /**
     * Method __construct
     *
     * @param NotificationRepository $notificationRepository [explicite description]
     *
     * @return void
     */
    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * Method index
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $notification =  NotificationResource::collection(
            $this->notificationRepository->getNotification($data)
        );
        if ($request->ajax()) {
            return $notification;
        }
        return view('client.notification.index', compact('notification'));
    }
    
    /**
     * Method create
     *
     * @return void
     */
    public function create()
    {
        //
    }
    
    /**
     * Method store
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function store(Request $request)
    {
        //
    }
    
    /**
     * Method show
     *
     * @param string $id [explicite description]
     *
     * @return void
     */
    public function show(string $id)
    {
        //
    }
    
    /**
     * Method edit
     *
     * @param string $id [explicite description]
     *
     * @return void
     */
    public function edit(string $id)
    {
        //
    }
    
    /**
     * Method update
     *
     * @param Request $request [explicite description]
     * @param string $id [explicite description]
     *
     * @return void
     */
    public function update(Request $request, string $id)
    {
        //
    }
    
    /**
     * Method destroy
     *
     * @param string $id [explicite description]
     *
     * @return void
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Method markAllRead
     *
     * @return void
     */
    public function markAllRead()
    {
        $this->notificationRepository->markAsRead();
        return back();
    }
}
