<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class Success extends Controller
{
    /**
     * Show the success page
     */
    public function __invoke(string $instruction)
    {
        $instruction = get_session_instruction($instruction);

        if (! $instruction) {
            return redirect()->route('error')->with('error', [
                'title' => 'Link não encontrado',
                'message' => 'Verifique o link ou Qr Code utilizado',
            ]);
        }

        return view('success', compact('instruction'));
    }
}
