<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApiKeyRequest;
use App\Services\ApiKeyService;

class ApiKeyController extends Controller
{
    public function __construct(private readonly ApiKeyService $apiKeyService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $limit = request()->query('limit', 5);

        return $this->apiKeyService->list($limit);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreApiKeyRequest $request)
    {
        return $this->apiKeyService->create($request->toDTO());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->apiKeyService->delete($id);
    }
}
