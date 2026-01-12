<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Repositories\FaqRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FaqRequest;
use App\Http\Resources\FaqResource;

class FaqController extends Controller
{
    protected $faqRepository;
        
    /**
     * Method __construct
     *
     * @param FaqRepository $faqRepository [explicite description]
     *
     * @return void
     */
    public function __construct(FaqRepository $faqRepository)
    {
        $this->faqRepository = $faqRepository;
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
        $faq = "";
        if ($request->ajax()) {
            $faq = FaqResource::collection(
                $this->faqRepository->getFaq($data)
            );
            return $faq;
        }
        return view('admin.faq.index', compact('faq'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $html = view('admin.faq.add-edit-faq')->render();
        return $this->successResponse('', $html);
    }
    
    /**
     * Method store
     *
     * @param FaqRequest $request [explicite description]
     *
     * @return void
     */
    public function store(FaqRequest $request)
    {
        $params = $request->all();
        $this->faqRepository->createFaq($params);
        return $this->successResponse(trans('message.success.add_faq'));
    }
    
    /**
     * Method edit
     *
     * @param $id $id [explicite description]
     *
     * @return void
     */
    public function edit($id)
    {
        $faq = $this->faqRepository->getFaqDetail($id);
        $html = view('admin.faq.add-edit-faq', compact('faq'))->render();
        return $this->successResponse('', $html);
       
    }
    
    /**
     * Method update
     *
     * @param FaqRequest $request [explicite description]
     * @param $id $id [explicite description]
     *
     * @return void
     */
    public function update(FaqRequest $request, $id)
    {
        
        $params = $request->all();
        $this->faqRepository->updateFaq($params, $id);
        return $this->successResponse(trans('message.success.update_faq'));
    
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
        $this->faqRepository->deleteFaq($id);
        return $this->successResponse(
            trans('message.success.faq_deleted')
        );
        
    }
}
