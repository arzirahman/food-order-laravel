<?php

namespace App\Http\Resources;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class MessageResource
{
    public static function error(int $code, string $message, $errors = null): HttpResponseException
    {
        $body = [
            "statusCode" => $code,
            "message" => $message,
            "status" => self::getStatus($code),
        ];
        if ($errors) {
            $body["errors"] = $errors;
        }
        throw new HttpResponseException(response($body, $code));
    }

    public static function success(int $code, string $message, $data, $total = null): Response
    {
        if ($total) {
            $body["total"] = $total;
        }
        $body["statusCode"] = $code;
        $body["message"] = $message;
        $body["status"] = self::getStatus($code);
        $body["data"] = $data;
        return response($body, $code);
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