<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpsertEventtRequest;
use App\Models\Eventt;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class EventtController extends Controller
{
    public function index(): LengthAwarePaginator
    {
        return Eventt::paginate(10);
    }
    
    public function store(UpsertEventtRequest $request): Eventt
    {
        return Eventt::create($request->validated());
    }

    public function show(Eventt $eventt): Eventt
    {
        return $eventt;
    }

    public function update(UpsertEventtRequest $request, Eventt $eventt): Eventt
    {
        $eventt->update($request->validated());

        return $eventt->fresh();
    }

    public function destroy(Eventt $eventt): array
    {
        $eventt->delete();

        return [];
    }
}
