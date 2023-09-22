<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class ApiBaseController extends Controller
{
    protected function response($data = [], $error = null, $status = 200)
    {
        // Если $error является исключением
        if ($error instanceof \Throwable) {
            // Записываем сообщение об ошибке в лог
            Log::error($error->getMessage());

            // Если debug = false
            if (!Config::get('app.debug')) {
                // Отправляем обобщенное сообщение об ошибке пользователю
                $error = "Произошла внутренняя ошибка сервера. Наши специалисты ее уже в пути!";
            } else {
                // Если дебаг включен, отправляем реальное сообщение об ошибке пользователю
                $error = $error->getMessage();
            }
        }

        $response = [
            'error'  => $error,
            'result' => $data
        ];

        return response()->json($response, $status);
    }
}
