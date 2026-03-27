<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SettingController extends Controller
{
    public function index()
    {
        Gate::authorize('manage-settings');
        $config = [
            'parque.name' => Setting::getValue('parque.name', config('parque.name')),
            'parque.city' => Setting::getValue('parque.city', config('parque.city')),
            'parque.state' => Setting::getValue('parque.state', config('parque.state')),
            'parque.contact' => Setting::getValue('parque.contact', config('parque.contact')),
        ];

        return view('settings.index', compact('config'));
    }

    public function update(Request $request)
    {
        Gate::authorize('manage-settings');
        $data = $request->validate([
            'parque.name' => 'required|string|max:255',
            'parque.city' => 'required|string|max:100',
            'parque.state' => 'required|string|max:100',
            'parque.contact' => 'required|string|max:255',
        ]);

        $parque = $data['parque'];

        foreach ($parque as $key => $value) {
            Setting::setValue('parque.'.$key, $value);
        }

        return redirect()->route('settings.index')->with('success', 'Configurações atualizadas com sucesso!');
    }
}
