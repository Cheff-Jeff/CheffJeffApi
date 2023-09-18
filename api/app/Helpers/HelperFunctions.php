<?php

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
