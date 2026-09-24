<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use App\Models\SystemSetting;
use Livewire\Component;

class SystemSettings extends Component
{
    // Pengaturan Digiflazz
    public string $digiflazzUsername = '';

    public string $digiflazzApiKey = '';

    public string $digiflazzWebhookSecret = '';

    // Pengaturan Tripay Gateway
    public string $tripayMerchantCode = '';

    public string $tripayApiKey = '';

    public string $tripayPrivateKey = '';

    // Pengaturan WhatsApp Bot (Baileys)
    public string $baileysEndpoint = '';

    public string $baileysToken = '';

    // Switcher & Maintenance Mode
    public bool $maintenanceMode = false;

    public string $maintenanceMessage = 'Sistem sedang dalam peningkatan performa rutin.';

    public string $activePPOBProvider = 'digiflazz'; // 'digiflazz', 'tokovoucher', 'vip'

    public function mount(): void
    {
        $this->digiflazzUsername = SystemSetting::get('digiflazz_username', config('services.digiflazz.username', ''));
        $this->digiflazzApiKey = SystemSetting::get('digiflazz_api_key', '');
        $this->digiflazzWebhookSecret = SystemSetting::get('digiflazz_webhook_secret', '');

        $this->tripayMerchantCode = SystemSetting::get('tripay_merchant_code', config('services.tripay.merchant_code', ''));
        $this->tripayApiKey = SystemSetting::get('tripay_api_key', '');
        $this->tripayPrivateKey = SystemSetting::get('tripay_private_key', '');

        $this->baileysEndpoint = SystemSetting::get('baileys_endpoint', config('services.baileys.endpoint', 'http://127.0.0.1:3000'));
        $this->baileysToken = SystemSetting::get('baileys_token', '');

        $this->maintenanceMode = (bool) SystemSetting::get('maintenance_mode', false);
        $this->maintenanceMessage = SystemSetting::get('maintenance_message', 'Sistem sedang dalam peningkatan performa rutin.');
        $this->activePPOBProvider = SystemSetting::get('active_ppob_provider', 'digiflazz');
    }

    public function saveDigiflazzSettings(): void
    {
        $this->validate([
            'digiflazzUsername' => 'required|string',
            'digiflazzApiKey' => 'nullable|string',
        ]);

        SystemSetting::set('digiflazz_username', $this->digiflazzUsername, 'digiflazz');
        if (! empty($this->digiflazzApiKey)) {
            SystemSetting::set('digiflazz_api_key', $this->digiflazzApiKey, 'digiflazz', true);
        }
        if (! empty($this->digiflazzWebhookSecret)) {
            SystemSetting::set('digiflazz_webhook_secret', $this->digiflazzWebhookSecret, 'digiflazz', true);
        }

        AuditLog::log('UPDATE_DIGIFLAZZ_SETTINGS', 'Memperbarui kredensial koneksi Digiflazz API');
        session()->flash('success_digiflazz', 'Konfigurasi Digiflazz berhasil disimpan.');
    }

    public function saveTripaySettings(): void
    {
        $this->validate([
            'tripayMerchantCode' => 'required|string',
        ]);

        SystemSetting::set('tripay_merchant_code', $this->tripayMerchantCode, 'tripay');
        if (! empty($this->tripayApiKey)) {
            SystemSetting::set('tripay_api_key', $this->tripayApiKey, 'tripay', true);
        }
        if (! empty($this->tripayPrivateKey)) {
            SystemSetting::set('tripay_private_key', $this->tripayPrivateKey, 'tripay', true);
        }

        AuditLog::log('UPDATE_TRIPAY_SETTINGS', 'Memperbarui kredensial koneksi Tripay Payment Gateway');
        session()->flash('success_tripay', 'Konfigurasi Tripay Gateway berhasil disimpan.');
    }

    public function saveBaileysSettings(): void
    {
        $this->validate([
            'baileysEndpoint' => 'required|url',
        ]);

        SystemSetting::set('baileys_endpoint', $this->baileysEndpoint, 'baileys');
        if (! empty($this->baileysToken)) {
            SystemSetting::set('baileys_token', $this->baileysToken, 'baileys', true);
        }

        AuditLog::log('UPDATE_BAILEYS_SETTINGS', 'Memperbarui endpoint WhatsApp Baileys Bot');
        session()->flash('success_baileys', 'Konfigurasi WhatsApp Bot berhasil disimpan.');
    }

    public function saveSystemSwitches(): void
    {
        SystemSetting::set('maintenance_mode', $this->maintenanceMode, 'system');
        SystemSetting::set('maintenance_message', $this->maintenanceMessage, 'system');
        SystemSetting::set('active_ppob_provider', $this->activePPOBProvider, 'system');

        AuditLog::log(
            'UPDATE_SYSTEM_SWITCHES',
            'Memperbarui saklar sistem: Maintenance Mode: '.($this->maintenanceMode ? 'ON' : 'OFF').", Provider Utama: {$this->activePPOBProvider}",
            ['maintenance' => $this->maintenanceMode, 'provider' => $this->activePPOBProvider]
        );

        session()->flash('success_system', 'Pengaturan saklar & provider berhasil diperbarui.');
    }

    public function render()
    {
        return view('livewire.admin.system-settings')->layout('layouts.app');
    }
}
