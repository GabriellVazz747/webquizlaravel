<?php
namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class ProfileInformationController
{
    protected $updater;

    public function __construct(UpdatesUserProfileInformation $updater)
    {
        $this->updater = $updater;
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        $this->updater->update($request->user(), $request->only('name', 'email'));

        return response()->json(['message' => 'Informações do perfil atualizadas']);
    }
}
