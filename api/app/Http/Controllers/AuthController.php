<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private $allowedParamsSignIn = ['email', 'password'];
    private $allowedParamsSignUp = ['first_name', 'last_name', 'email', 'password', 'right_id'];

    public function signIn(Request $request)
    {
        if ($request->missing($this->allowedParamsSignIn)) {
            return sendError("Missing required parameters.", 422);
        }

        $filteredRequest = $request->only($this->allowedParamsSignIn);
        $user = User::where('email', $filteredRequest['email'])->first();

        if ($user === null) {
            return sendError("User not found.", 404);
        }

        if (Hash::check($filteredRequest['password'], $user->password)) {
            return authorize($user->createToken('apiToken')->plainTextToken);
        } else {
            return sendError("Authentication failed.", 401);
        }
    }

    public function signUp(Request $request)
    {
        if ($request->missing($this->allowedParamsSignUp)) {
            return sendError("Missing required parameters.", 422);
        }
        $filteredRequest = $request->only($this->allowedParamsSignUp);
        $authUser = $request->user();
        if ($authUser->right->right === "editor") {
            sendError("You do not have permission to do this.", 403);
        }

        $user = new User();
        $validation = $user->validate($filteredRequest);

        if ($validation->fails()) {
            return sendError($validation->errors(), 422);
        }

        $user->fill($filteredRequest);

        if (!isset($filteredRequest['right_id'])) {
            $user->right_id = UserRight::where('right', 'editor')->first()->id;
        } else {
            $user->right_id = UserRight::findOrFail($filteredRequest['right_id'])->id;
        }

        $user->fill(['password' => Hash::make(
            $filteredRequest['password'],
            ['rounds' => 12]
        )])->save();

        return authorize($user->createToken('apiToken')->plainTextToken);
    }

    public function signOff(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        return sendSuccess("Signed off.");
    }
}
