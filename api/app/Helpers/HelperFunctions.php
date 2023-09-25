<?php

use Illuminate\Http\Request;

function sendError($message, $code)
{
    return response()->json([
        "errors" => $message
    ], $code);
}

function sendSuccess($message)
{
    return response()->json([
        "message" => $message
    ]);
}

function authorize($token)
{
    return response()->json([
        'authorization' => [
            'token' => $token,
            'type' => 'bearer'
        ]
    ]);
}


function checkSuperAdmin(Request $request)
{
    return [
        "isSuperAdmin" => $request->user()->right->right === "supper-admin" ? true : false,
        "websiteId" => $request->header('websiteTarget')
    ];
}
