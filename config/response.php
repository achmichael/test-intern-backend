<?php

namespace Config;

class Response {
    
    public static function success($data) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode([
            'status' => true,
            'data' => $data
        ]);
    }
    
    public static function error($message, $statusCode = 400) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode([
            'status' => false,
            'message' => $message
        ]);
    }
    
    public static function msg($kode, $message) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode([
            'kode' => $kode,
            'message' => $message
        ]);
    }
}
?>
