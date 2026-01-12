<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\{CompanyWebsitesRepository};

class CompanyWebsiteController extends Controller
{

    protected $companyWebsitesRepository;

    public function __construct(
        CompanyWebsitesRepository $companyWebsitesRepository,
    ) {
        $this->companyWebsitesRepository = $companyWebsitesRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function list(Request $request)
    {
        $search = $request->input('search', '');

        $companyWebsites = $this->companyWebsitesRepository
            ->getAllCompanyWebsites(['search' => $search], false);

        $result = $companyWebsites->map(function ($item) {
            return [
                'id'   => $item->id,
                'text' => $item->website,
            ];
        });

        return response()->json($result);
    }
}
