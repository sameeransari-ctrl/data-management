<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\{DesignationRepository};

class DesignationController extends Controller
{
    protected $designationRepository;

    public function __construct(
        DesignationRepository $designationRepository,
    ) {
        $this->designationRepository = $designationRepository;
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

    // public function list(Request $request)
    // {
    //     // $search = $request->input('search', '');
    //     // $designations = $this->designationRepository->getAllDesignation(['search' => $search], false)
    //     //     ->pluck('name');

    //     // // Convert to Select2 format
    //     // $result = $designations->map(function($item) {
    //     //     return ['id' => $item, 'text' => $item];
    //     // });

    //     // return response()->json($result);

    //     $search = $request->input('search', '');

    //     $designations = $this->designationRepository
    //         ->getAllDesignation(['search' => $search], false);

    //     $result = $designations->map(function ($item) {
    //         return [
    //             'id'   => $item->id,   // ✅ numeric ID
    //             'text' => $item->name, // ✅ display name
    //         ];
    //     });

    //     return response()->json($result);
    // }


    public function list(Request $request)
    {
        $search = $request->input('search', '');

        $designations = $this->designationRepository
            ->getAllDesignation([
                'search' => $search,
                'size'   => $request->input('size', 20)
            ], false);

        $result = $designations->map(function ($item) {
            return [
                'id'   => $item->name, // ✅ STRING ID (REQUIRED)
                'text' => $item->name,
            ];
        })->values(); // ✅ important

        return response()->json($result);
    }
}
