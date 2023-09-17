<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function signIn(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user === null) {
            return response()->json(['errors' => 'No user with that email.'], 404);
        }

        if (Hash::check($request->password, $user->password)) {
            return response()->json([
                'user' => $user,
                'authorization' => [
                    'token' => $user->createToken('apiToken')->plainTextToken,
                    'type' => 'bearer'
                ]
            ]);
        } else {
            return response()->json(['errors' => 'Authentication failed.'], 422);
        }
    }

    public function signUp(Request $request)
    {
        $user = new User();
        $rightId = 0;
        $validation = $user->validate($request->all());

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()], 422);
        }

        if ($request->right === null) {
            $right = UserRight::where('right', 'editor')->first();
            $rightId = $right->id;
        } else {
            $right = UserRight::where('right', $request->right)->first();
            $rightId = $right->id;
        }

        $user->fill($request->all());
        $user->right_id = $rightId;
        $user->fill(['password' => Hash::make(
            $request->password,
            ['rounds' => 12]
        )])->save();

        return response()->json([
            'authorization' => [
                'token' => $user->createToken('apiToken')->plainTextToken,
                'type' => 'bearer'
            ]
        ]);
    }

    public function signOff(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        return response()->json(['message' => 'Successfully signed off.']);
    }
}
