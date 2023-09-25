<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private $allowedParamsUpdate = ['first_name', 'last_name', 'email', 'password', 'right_id'];
    private $allowedParamsUpdateRole = ['role_id', 'user_id'];
    private $allowedParamsDelete = ['user_id'];

    public function index()
    {
        return response()->json(User::all());
    }

    public function show($id)
    {
        $user = User::find($id);
        if ($user === null) {
            return response()->json(["errors" => "User not found."], 404);
        }
        return response()->json($user);
    }

    public function update(Request $request)
    {
        $user = new User();
        $validation = $user->validateUpdate($request->only($this->allowedParamsUpdate));
        if ($validation->fails()) {
            return sendError($validation->errors(), 422);
        }

        $filteredRequest = $request->only($this->allowedParamsUpdate);
        $user = $request->user();
        if (isset($filteredRequest['first_name'])) {
            $user->first_name = $filteredRequest['first_name'];
        }
        if (isset($filteredRequest['last_name'])) {
            $user->last_name = $filteredRequest['last_name'];
        }
        if (isset($filteredRequest['email'])) {
            $user->email = $filteredRequest['email'];
        }
        if (isset($filteredRequest['password'])) {
            $user->fill(['password' => Hash::make(
                $filteredRequest['password'],
                ['rounds' => 12]
            )]);
        }
        $user->save();
        return sendSuccess("User updated.");
    }

    public function updateRole(Request $request)
    {
        if ($request->missing($this->allowedParamsUpdateRole)) {
            return sendError("Missing required parameters.", 422);
        }

        $authUser = $request->user();
        if ($authUser->right->right === "editor") {
            return sendError("You do not have permission to do this.", 403);
        }

        $filteredRequest = $request->only($this->allowedParamsUpdateRole);

        $user = User::findOrFail($filteredRequest['user_id']);
        $user->role_id = UserRight::findOrFail($filteredRequest['role_id'])->id;
        $user->save();

        return sendSuccess("User updated.");
    }

    public function destroy(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        $user->delete();
        return sendSuccess("User deleted.");
    }

    public function destroyUser(Request $request)
    {
        if ($request->missing($this->allowedParamsDelete)) {
            return sendError("Missing required parameters.", 422);
        }

        $authUser = $request->user();
        if ($authUser->right->right === "editor") {
            return sendError("You do not have permission to do this.", 403);
        }

        $filteredRequest = $request->only($this->allowedParamsDelete);
        $user = User::findOrFail($filteredRequest['user_id']);

        $user->tokens()->delete();
        $user->delete();
        return sendSuccess("User deleted.");
    }
}
