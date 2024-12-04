<?php

function errorWrapper(callable $func) {
    try {
        return $func();
    } catch (Exception $e) {
        return response()->json(
            [
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTrace()
                ]
            ],
            500
        );
    }
}