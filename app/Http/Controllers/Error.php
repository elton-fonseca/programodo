<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class Error extends Controller
{
    /**
     * Show the error page
     */
    public function __invoke(?string $instruction = null)
    {
        if ($instruction) {
            $instruction = get_session_instruction($instruction);
        }

        return view('error', compact('instruction'));
    }
}
