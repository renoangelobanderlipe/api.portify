<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTechStackRequest;
use App\Models\TechStack;
use App\Services\TechStackService;

class TechStackController extends Controller
{
    public function __construct(private readonly TechStackService $techStackService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->techStackService->list();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTechStackRequest $request)
    {
        return $this->techStackService->store($request->toDTO());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TechStack $techStack)
    {
        return $this->techStackService->delete($techStack->id);
    }
}
