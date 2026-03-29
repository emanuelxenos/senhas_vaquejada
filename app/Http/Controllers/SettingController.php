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
            'payment.gateway' => Setting::getValue('payment.gateway', 'none'),
            'payment.asaas_api_key' => Setting::getValue('payment.asaas_api_key', ''),
            'payment.asaas_env' => Setting::getValue('payment.asaas_env', 'sandbox'),
            'payment.pagseguro_token' => Setting::getValue('payment.pagseguro_token', ''),
            'payment.pagseguro_env' => Setting::getValue('payment.pagseguro_env', 'sandbox'),
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
            'payment.gateway' => 'nullable|string|in:none,asaas,pagseguro',
            'payment.asaas_api_key' => 'nullable|string',
            'payment.asaas_env' => 'nullable|string|in:sandbox,production',
            'payment.pagseguro_token' => 'nullable|string',
            'payment.pagseguro_env' => 'nullable|string|in:sandbox,production',
        ]);

        if (isset($data['parque'])) {
            foreach ($data['parque'] as $key => $value) {
                Setting::setValue('parque.'.$key, $value);
            }
        }
        
        if (isset($data['payment'])) {
            foreach ($data['payment'] as $key => $value) {
                // Ignore nulls to avoid overwriting with empty unless explicit
                Setting::setValue('payment.'.$key, $value ?? '');
            }
        }

        return redirect()->route('settings.index')->with('success', 'Configurações atualizadas com sucesso!');
    }
}
