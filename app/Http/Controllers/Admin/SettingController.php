<?php
namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use App\Repositories\SettingRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingRequest;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    protected $settingRepository;

    /**
     * CmsController constructor.
     *
     * @param SettingRepository $settingRepository
     */
    public function __construct(SettingRepository $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = $this->settingRepository->getAll();

        return view('admin.setting.index', compact('settings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
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
        $params = $request->except(['_token', '_method']);
        $result = $this->settingRepository->updateDetail($params);
        if (!empty($result)) {
            return $this->successResponse(__('message.success.content_updated'));
        }
    }
    
    /**
     * Method show
     *
     * @param $id $id [explicite description]
     *
     * @return void
     */
    public function show($id)
    {
        //
    }
    
    /**
     * Method edit
     *
     * @param int $id [explicite description]
     *
     * @return void
     */
    public function edit($id)
    {
        $setting = $this->settingRepository->getDetail($id);
        $html = view('admin.setting.edit', compact('setting'))->render();
        return $this->successResponse('', $html);

    }
        
    /**
     * Method update
     *
     * @param SettingRequest $request [explicite description]
     * @param $id $id [explicite description]
     *
     * @return void
     */
    public function update(SettingRequest $request, $id)
    {

        $params = $request->except(['_token', '_method']);
        $result = $this->settingRepository->updateDetail($params, $id);
        if (!empty($result)) {
            return $this->successResponse(__('message.success.content_updated'));
        }

    }
    
    /**
     * Method destroy
     *
     * @param $id $id [explicite description]
     *
     * @return void
     */
    public function destroy($id)
    {
        //
    }
    
    /**
     * Method runCommand
     *
     * @param $slug $slug [explicite description]
     *
     * @return void
     */
    public function runCommand($slug)
    {
        $execute = $this->settingRepository->runCommand($slug);
        if ($execute) {
            return $this->successResponse(__('message.success.content_updated'));
        } else {
            return $this->errorResponse(__('message.error.something_went_wrong'));
        }
    }

    /**
     * Display a listing of the artisanCommandList.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function generalList()
    {
        $maintenanceMode = true;
        if (! file_exists(storage_path('framework/down'))) {
            $maintenanceMode = false;
        }
        return view('admin.setting.general', compact('maintenanceMode'));
    }
}
