<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
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
}
