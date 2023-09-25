<?php

namespace App\Http\Controllers;

use App\Enum\Role;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\UpsertUserRequest;
use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(): Collection
    {
        return User::all();
    }

    public function store(UpsertUserRequest $request): User
    {
        return User::create($request->validated());
    }

    public function show(User $user): User
    {
        return $user;
    }

    public function update(UpsertUserRequest $request, User $user): User
    {
        $user->update($request->validated());

        return $user->fresh();
    }

    public function destroy(User $user): array
    {
        $user->delete();

        return [];
    }

    public function me(Request $request): User
    {
        return $request->user();
    }

    public function updateMe(UpsertUserRequest $request): User
    {
        // Remove the ability for any users to change role
        $request->offsetUnset('role');

        return $this->update($request, $request->user());
    }

    public function register(RegistrationRequest $request): User
    {
        return User::create(array_merge($request->validated(), [
            'role' => Role::USER, // Or create a config that specifies the app's default user role
        ]));
    }

    public function logout(Request $request): array
    {
        $request->user()->token()->revoke();

        return [];
    }
}
