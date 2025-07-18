<?php

namespace App\Exceptions;

use Exception;

class InvalidStreamException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'message' => $this->getMessage(),
            'image' => asset('images/not-found.png'),
        ], 404);
    }
}
