<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function signIn($email, $password)
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['errors' => 'No user with that email.'], 422);
        }

        if (Hash::check($password, $user->password)) {
            // TODO: return JWT token
        } else {
            return response()->json(['errors' => 'Authentication failed.'], 422);
        }
    }

    public function signUp(Request $request)
    {
        $user = new User();
        $validation = $user->validate($request->all());

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()], 422);
        }

        $user->fill($request->all());
        $user->fill(['password' => Hash::make(
            $request->password,
            ['rounds' => 12]
        )])->save();
        //TODO: return JWT token
    }

    public function signOff()
    {
        // TODO: Delete JWT token
    }
}
