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
            'parque.preco_senha' => Setting::getValue('parque.preco_senha', '100.00'),
            'parque.logo' => Setting::getValue('parque.logo', ''),
            'payment.gateway' => Setting::getValue('payment.gateway', 'none'),
            'payment.asaas_api_key' => Setting::getValue('payment.asaas_api_key', ''),
            'payment.asaas_env' => Setting::getValue('payment.asaas_env', 'sandbox'),
            'payment.pagseguro_token' => Setting::getValue('payment.pagseguro_token', ''),
            'payment.pagseguro_env' => Setting::getValue('payment.pagseguro_env', 'sandbox'),
            'senha.bois_amador' => Setting::getValue('senha.bois_amador', '3'),
            'senha.bois_profissional' => Setting::getValue('senha.bois_profissional', '2'),
            'senha.bois_boi_tv' => Setting::getValue('senha.bois_boi_tv', '2'),
            'senha.data_limite_boi_tv' => Setting::getValue('senha.data_limite_boi_tv', ''),
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
            'parque.preco_senha' => 'required|numeric|min:0',
            'parque_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'payment.gateway' => 'nullable|string|in:none,asaas,pagseguro',
            'payment.asaas_api_key' => 'nullable|string',
            'payment.asaas_env' => 'nullable|string|in:sandbox,production',
            'payment.pagseguro_token' => 'nullable|string',
            'payment.pagseguro_env' => 'nullable|string|in:sandbox,production',
            'senha.bois_amador' => 'required|integer|min:1|max:10',
            'senha.bois_profissional' => 'required|integer|min:1|max:10',
            'senha.bois_boi_tv' => 'required|integer|min:1|max:10',
            'senha.data_limite_boi_tv' => 'nullable|date',
        ]);

        if (isset($data['parque'])) {
            foreach ($data['parque'] as $key => $value) {
                Setting::setValue('parque.'.$key, $value);
            }
        }

        if ($request->hasFile('parque_logo')) {
            $file = $request->file('parque_logo');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            // Salvar no diretório público 'uploads'
            $file->move(public_path('uploads'), $filename);
            Setting::setValue('parque.logo', 'uploads/' . $filename);
        }
        
        if (isset($data['payment'])) {
            foreach ($data['payment'] as $key => $value) {
                Setting::setValue('payment.'.$key, $value ?? '');
            }
        }

        if (isset($data['senha'])) {
            foreach ($data['senha'] as $key => $value) {
                Setting::setValue('senha.'.$key, $value ?? '');
            }
        }

        return redirect()->route('settings.index')->with('success', 'Configurações atualizadas com sucesso!');
    }
}
