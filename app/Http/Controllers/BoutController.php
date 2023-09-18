<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpsertBoutRequest;
use App\Models\Bout;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class BoutController extends Controller
{
    public function index(): Collection
    {
        return Bout::all();
    }

    public function store(UpsertBoutRequest $request): Bout
    {
        return Bout::create($request->validated());
    }

    public function show(Bout $bout): Bout
    {
        return $bout;
    }

    public function update(UpsertBoutRequest $request, Bout $bout)
    {
        $bout->update($request->validated());

        return $bout->fresh();
    }

    public function destroy(Bout $bout): array
    {
        $bout->delete();

        return [];
    }
}
