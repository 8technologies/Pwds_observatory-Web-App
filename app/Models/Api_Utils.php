<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api_Utils extends Model
{
    //function for returning a successful json object
    public static function success($data = null, $message = null, $status = 200)
    {
        header('Content-Type: application/json');
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $status
        ]);
    }

    //function for returning an error json object
    public static function error($message = null, $status = 400)
    {
        return response()->json([
            'message' => $message,
            'status' => $status
        ]);
    }
}
