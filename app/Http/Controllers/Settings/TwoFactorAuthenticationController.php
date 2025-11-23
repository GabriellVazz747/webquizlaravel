<?php
namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;

class TwoFactorAuthenticationController
{
    public function store(Request $request)
    {
        $user = $request->user();

        if ($user->two_factor_secret) {
            return response()->json(['message' => '2FA já habilitado'], 400);
        }

        $user->enableTwoFactorAuthentication();

        return response()->json([
            'message' => '2FA habilitado',
            'qr_code' => $user->twoFactorQrCodeSvg(), // se quiser mostrar QR
            'recovery_codes' => $user->recoveryCodes()
        ]);
    }

    public function destroy(Request $request)
    {
        $user = $request->user();
        $user->disableTwoFactorAuthentication();

        return response()->json(['message' => '2FA desabilitado']);
    }
}
