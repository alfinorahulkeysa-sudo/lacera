<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    /**
     * Tampilkan form pengaturan toko.
     */
    public function index()
    {
        $settings = [
            'store_name'           => Setting::get('store_name', 'Lacera Official Store'),
            'store_email'          => Setting::get('store_email', 'info@lacera.com'),
            'store_phone'          => Setting::get('store_phone', '0812-3456-7890'),
            'store_address'        => Setting::get('store_address', 'Jl. Kecantikan No. 10, Jakarta Selatan'),
            'flat_shipping_cost'   => Setting::get('flat_shipping_cost', '15000'),
            'base_shipping_cost'   => Setting::get('base_shipping_cost', '10000'),
            'per_kg_shipping_cost' => Setting::get('per_kg_shipping_cost', '5000'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Simpan pembaruan pengaturan toko.
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'store_name'           => 'required|string|max:100',
            'store_email'          => 'required|email|max:100',
            'store_phone'          => 'required|string|max:20',
            'store_address'        => 'required|string',
            'flat_shipping_cost'   => 'required|numeric|min:0',
            'base_shipping_cost'   => 'required|numeric|min:0',
            'per_kg_shipping_cost' => 'required|numeric|min:0',
        ]);

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->route('admin.settings')
            ->with('success', 'Pengaturan toko berhasil disimpan!');
    }
}
