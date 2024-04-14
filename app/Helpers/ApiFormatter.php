<?php


namespace App\Helpers;
// namespace :menentukan lokasi folder dari file ini
//nama class nya harus sama dengan nama file nya

class ApiFormatter {
    // bikin variable struktur data yang akan di tampilkan di response postman
    protected static $response = [
        "status" => NULL,
        "message" => NULL,
        "data" => NULL
    ];

    public static function sendResponse($status = NULL, $message = NULL, $data = [])
    {
        self::$response['status'] = $status;
        self::$response['message'] = $message;
        self::$response['data'] = $data;
        return response()->json(self::$response, self::$response['status']);
        //status : http status code (200, 400, 500)
        //message : decs http status code ('success', 'bad request', 'server error')
        //data : hasil yang diambil dari db
    }
}