<?php

namespace App\Services;

use App\DTO\TechStack\CreateTechStackDTO;
use Illuminate\Support\Facades\Auth;

class TechStackService
{
    public function list()
    {
        return Auth::user()->techStacks()->orderBy('created_at', 'desc')->get();
    }

    public function store(CreateTechStackDTO $createTechStackDTO)
    {
        Auth::user()->techStacks()->create($createTechStackDTO->toArray());

        return response()->noContent();
    }

    public function delete(string $id)
    {
        Auth::user()->techStacks()->findOrFail($id)->delete();

        return response()->noContent();
    }
}
