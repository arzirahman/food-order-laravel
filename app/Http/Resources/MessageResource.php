<?php

namespace App\Http\Resources;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class MessageResource
{
    public static function error(int $code, string $message, $errors): HttpResponseException
    {
        throw new HttpResponseException(response([
            "statusCode" => $code,
            "message" => $message,
            "status" => self::getStatus($code),
            "errors" => $errors
        ], $code));
    }

    public static function success(int $code, string $message, $data): Response
    {
        return response([
            "statusCode" => $code,
            "message" => $message,
            "status" => self::getStatus($code),
            "data" => $data
        ], $code);
    }

    public static function getStatus(int $code)
    {
        switch ($code) {
            case 200:
                return 'OK';
            case 201:
                return 'Created';
            case 400:
                return 'Bad Request';
            case 401:
                return 'Unauthorize';
            case 403:
                return 'Forbidden';
            case 404:
                return 'Not Found';
            case 500:
                return 'Internal Server Error';
            default:
                return null;
        }
    }
}