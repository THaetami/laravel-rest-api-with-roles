<?php

namespace App\Helpers;

class JsonResponseHelper
{
    public static function respondErrorNotFound($message, $statusCode = 404)
    {
        return response()->json([
            "status" => "fail",
            "message" => $message
        ], $statusCode);
    }

    public static function respondErrorForbidden($message, $statusCode = 403)
    {
        return response()->json([
            "status" => "fail",
            "message" => $message
        ], $statusCode);
    }

    public static function respondNoContent($message, $statusCode = 204)
    {
        return response()->json([
            "status" => "fail",
            "message" => $message
        ], $statusCode);
    }

    public static function respondFail($message, $statusCode = 400)
    {
        return response()->json([
            "status" => "fail",
            "message" => $message
        ], $statusCode);
    }

    public static function respondSuccess($data, $statusCode = 200)
    {
        return response()->json([
            "status" => "success",
            "data" => $data
        ], $statusCode);
    }
}
