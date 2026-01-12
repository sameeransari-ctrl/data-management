<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use App\Repositories\CmsRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CmsRequest;

class CmsController extends Controller
{
    protected $cmsRepository;

    /**
     * CmsController constructor.
     *
     * @param CmsRepository $cmsRepository
     */
    public function __construct(CmsRepository $cmsRepository)
    {
        $this->cmsRepository = $cmsRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
        //
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
     * @param $slug $slug [explicite description]
     *
     * @return void
     */
    public function edit($slug)
    {
        
        $cms = $this->cmsRepository->getCmsDetails($slug);
        if (empty($cms)) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.cms.index', compact('cms'));
        
    }
    
    /**
     * Method update
     *
     * @param CmsRequest $request [explicite description]
     * @param $id      $id [explicite description]
     *
     * @return void
     */
    public function update(CmsRequest $request, $id)
    {
        
        $params = $request->except(['_token', '_method']);
        $params['id'] = $id;
        $result = $this->cmsRepository->updateCms($params);
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

}