<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\PaymentCb48;
use App\Actions\PaymentProcess;
use App\Actions\PaymentShow;
use App\Exceptions\InstructionException;
use App\Exceptions\PaymentException;
use App\Http\Requests\ProcessPaymentRequest;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentShow $paymentShow,
        private PaymentCb48 $paymentCb48,
        private PaymentProcess $paymentProcess
    ) {}

    public function show(string $instructionId)
    {
        try {
            $instruction = $this->paymentShow->execute($instructionId);

            set_session_instruction($instruction);

            return view('payment', compact('instruction'));
        } catch (InstructionException $e) {
            return redirect()->route('error', $instructionId)->with('error', [
                'title' => $e->getTitle(),
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function cb48(string $cb48)
    {
        try {
            $instruction = $this->paymentCb48->execute($cb48);

            set_session_instruction($instruction);

            return view('payment', compact('instruction'));
        } catch (InstructionException $e) {
            return redirect()->route('error')->with('error', [
                'title' => $e->getTitle(),
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function process(ProcessPaymentRequest $request)
    {
        try {
            $instruction = get_session_instruction($request->get('instruction_id'));

            if (! $instruction) {
                abort(404);
            }

            $paymentPageLink = $this->paymentProcess->execute(
                $instruction,
                $request->all()
            );

            return redirect()->away($paymentPageLink);
        } catch (InstructionException|PaymentException $e) {
            return redirect()->route('error')->with('error', [
                'title' => $e->getTitle(),
                'message' => $e->getMessage(),
            ]);
        }
    }
}
