<?php
namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class PasswordController
{
    protected $updater;

    public function __construct(UpdatesUserPasswords $updater)
    {
        $this->updater = $updater;
    }

    public function update(Request $request)
    {
        // O usuário já vem do Sanctum: $request->user()
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $this->updater->update($request->user(), $request->only('password'));

        return response()->json(['message' => 'Senha atualizada com sucesso']);
    }
}
