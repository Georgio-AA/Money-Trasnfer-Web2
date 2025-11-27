<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SettingsController extends Controller
{
    protected $settingsFile;

    public function __construct()
    {
        $this->settingsFile = storage_path('app/admin_settings.json');
        
        // Create default settings if file doesn't exist
        if (!File::exists($this->settingsFile)) {
            $this->createDefaultSettings();
        }
    }

    public function index()
    {
        $settings = $this->getSettings();
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'platform_name' => 'required|string|max:100',
            'platform_email' => 'required|email',
            'transfer_fee_percentage' => 'required|numeric|min:0|max:100',
            'transfer_fee_fixed' => 'required|numeric|min:0',
            'min_transfer_amount' => 'required|numeric|min:0',
            'max_transfer_amount' => 'required|numeric|min:0',
            'daily_transfer_limit' => 'required|numeric|min:0',
        ]);

        $settings = [
            'platform_name' => $request->platform_name,
            'platform_email' => $request->platform_email,
            'transfer_fee_percentage' => (float) $request->transfer_fee_percentage,
            'transfer_fee_fixed' => (float) $request->transfer_fee_fixed,
            'min_transfer_amount' => (float) $request->min_transfer_amount,
            'max_transfer_amount' => (float) $request->max_transfer_amount,
            'daily_transfer_limit' => (float) $request->daily_transfer_limit,
            'maintenance_mode' => $request->has('maintenance_mode') ? true : false,
            'updated_at' => now()->toDateTimeString(),
        ];

        File::put($this->settingsFile, json_encode($settings, JSON_PRETTY_PRINT));

        return back()->with('success', 'Settings updated successfully');
    }

    protected function getSettings()
    {
        if (File::exists($this->settingsFile)) {
            return json_decode(File::get($this->settingsFile), true);
        }
        
        return $this->createDefaultSettings();
    }

    protected function createDefaultSettings()
    {
        $defaults = [
            'platform_name' => 'SwiftPay',
            'platform_email' => 'admin@swiftpay.com',
            'transfer_fee_percentage' => 2.5,
            'transfer_fee_fixed' => 1.00,
            'min_transfer_amount' => 10.00,
            'max_transfer_amount' => 10000.00,
            'daily_transfer_limit' => 50000.00,
            'maintenance_mode' => false,
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString(),
        ];

        File::put($this->settingsFile, json_encode($defaults, JSON_PRETTY_PRINT));
        
        return $defaults;
    }
}
