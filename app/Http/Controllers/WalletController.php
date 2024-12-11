<?php

namespace App\Http\Controllers;

use App\Actions\AppleValidateMerchant;
use App\Actions\WalletProcess;
use App\Exceptions\InstructionException;
use App\Exceptions\PaymentException;
use App\Http\Requests\WalletProcessRequest;
use Illuminate\Http\Request;
use Throwable;

class WalletController extends Controller
{
    public function __construct(
        private WalletProcess $walletProcess,
        private AppleValidateMerchant $appleValidateMerchant
    ) {}

    public function process(WalletProcessRequest $request)
    {
        try {
            $instruction = get_session_instruction($request->get('instruction_id'));

            if (! $instruction) {
                abort(404);
            }

            $paymentPageLink = $this->walletProcess->execute(
                $instruction,
                $request->all()
            );

            return ['redirect' => $paymentPageLink];
        } catch (InstructionException|PaymentException $e) {
            return redirect()->route('error')->with('error', [
                'title' => $e->getTitle(),
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function validateMerchant(Request $request)
    {
        $request->validate([
            'validationURL' => ['required', 'url'],
        ]);

        try {
            $validationData = $this->appleValidateMerchant->execute(
                $request->validationURL
            );

            return $validationData;
        } catch (Throwable $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
