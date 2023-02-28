<?php

namespace Lib;

class ResponseHttp
{
    final public static function statusMessage(int $status, string $answer): string
    {
        http_response_code($status);

        $message = [
            'status' => self::getStatusMessage($status),
            'message' => $answer
        ];
        return json_encode($message);
    }

    private static function getStatusMessage($code): string
    {

        $statusMessage = [
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            204 => 'No Content',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            503 => 'Service Unavailable',
        ];
        return $statusMessage[$code] ?? $statusMessage[500];
    }

    public static function setHeaders(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Max-Age: 3600');
        header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
    }


}