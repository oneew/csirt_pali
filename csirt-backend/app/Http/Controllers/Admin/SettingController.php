<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Display settings page
     */
    public function index()
    {
        try {
            // Group settings by category
            $settingsGroups = [
                'general' => Setting::byGroup('general')->get(),
                'security' => Setting::byGroup('security')->get(),
                'email' => Setting::byGroup('email')->get(),
                'notifications' => Setting::byGroup('notifications')->get(),
                'appearance' => Setting::byGroup('appearance')->get(),
                'integrations' => Setting::byGroup('integrations')->get(),
            ];

            // If no settings exist, create default ones
            if ($settingsGroups['general']->isEmpty()) {
                $this->createDefaultSettings();
                $settingsGroups['general'] = Setting::byGroup('general')->get();
            }

            return view('admin.settings.index', compact('settingsGroups'));
            
        } catch (\Exception $e) {
            return view('admin.settings.index', ['settingsGroups' => []]);
        }
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'settings' => 'required|array',
            'settings.*' => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $oldSettings = [];
            $updatedCount = 0;

            foreach ($request->settings as $key => $value) {
                $setting = Setting::where('key', $key)->first();
                
                if ($setting) {
                    $oldSettings[$key] = $setting->value;
                    
                    if ($setting->value !== $value) {
                        $setting->update(['value' => $value]);
                        $updatedCount++;
                    }
                } else {
                    // Create new setting if it doesn't exist
                    Setting::create([
                        'key' => $key,
                        'value' => $value,
                        'type' => $this->guessSettingType($value),
                        'group' => $this->guessSettingGroup($key),
                        'description' => ucwords(str_replace(['_', '-'], ' ', $key)),
                        'is_public' => false
                    ]);
                    $updatedCount++;
                }
            }

            // Clear settings cache
            Cache::tags(['settings'])->flush();

            // Log activity
            ActivityLog::logActivity(
                'updated',
                "Updated {$updatedCount} system settings",
                null,
                $oldSettings,
                $request->settings
            );

            return redirect()->route('admin.settings.index')
                ->with('success', "Updated {$updatedCount} settings successfully.");
                
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while updating settings. Please try again.');
        }
    }

    /**
     * Reset settings to default
     */
    public function reset(Request $request)
    {
        $request->validate([
            'group' => 'required|string|in:general,security,email,notifications,appearance,integrations'
        ]);

        try {
            $settings = Setting::byGroup($request->group)->get();
            $oldData = $settings->pluck('value', 'key')->toArray();

            // Reset to default values
            $defaultValues = $this->getDefaultSettings()[$request->group] ?? [];
            
            foreach ($settings as $setting) {
                if (isset($defaultValues[$setting->key])) {
                    $setting->update(['value' => $defaultValues[$setting->key]]);
                }
            }

            // Clear settings cache
            Cache::tags(['settings'])->flush();

            // Log activity
            ActivityLog::logActivity(
                'reset',
                "Reset {$request->group} settings to default",
                null,
                $oldData,
                $defaultValues
            );

            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred.'], 500);
        }
    }

    /**
     * Export settings
     */
    public function export(Request $request)
    {
        try {
            $settings = Setting::all();

            $exportData = [];
            foreach ($settings as $setting) {
                $exportData[] = [
                    'key' => $setting->key,
                    'value' => $setting->value,
                    'type' => $setting->type,
                    'group' => $setting->group,
                    'description' => $setting->description,
                    'is_public' => $setting->is_public ? 'true' : 'false'
                ];
            }

            $filename = 'settings_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
            
            $handle = fopen('php://temp', 'w');
            
            // Add header
            fputcsv($handle, ['Key', 'Value', 'Type', 'Group', 'Description', 'Is Public']);
            
            foreach ($exportData as $row) {
                fputcsv($handle, $row);
            }
            
            rewind($handle);
            $csv = stream_get_contents($handle);
            fclose($handle);

            return response($csv)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
                
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while exporting settings. Please try again.');
        }
    }

    /**
     * Create default settings
     */
    private function createDefaultSettings()
    {
        $defaultSettings = $this->getDefaultSettings();

        foreach ($defaultSettings as $group => $settings) {
            foreach ($settings as $key => $value) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    [
                        'value' => $value,
                        'type' => $this->guessSettingType($value),
                        'group' => $group,
                        'description' => ucwords(str_replace(['_', '-'], ' ', $key)),
                        'is_public' => in_array($key, $this->getPublicSettings())
                    ]
                );
            }
        }
    }

    /**
     * Get default settings values
     */
    private function getDefaultSettings()
    {
        return [
            'general' => [
                'organization_name' => 'CSIRT PALI',
                'organization_description' => 'Computer Security Incident Response Team',
                'organization_mission' => 'To protect and secure digital infrastructure',
                'organization_vision' => 'A secure digital environment for all',
                'organization_address' => '',
                'organization_phone' => '',
                'organization_email' => 'contact@csirt.gov',
                'timezone' => 'Asia/Jakarta',
                'language' => 'en',
                'date_format' => 'Y-m-d',
                'time_format' => 'H:i:s'
            ],
            'security' => [
                'session_timeout' => '120',
                'max_login_attempts' => '5',
                'lockout_duration' => '15',
                'require_email_verification' => 'true',
                'require_admin_approval' => 'true',
                'password_min_length' => '8',
                'password_require_uppercase' => 'true',
                'password_require_lowercase' => 'true',
                'password_require_numbers' => 'true',
                'password_require_symbols' => 'false'
            ],
            'email' => [
                'mail_driver' => 'smtp',
                'mail_host' => 'localhost',
                'mail_port' => '587',
                'mail_username' => '',
                'mail_password' => '',
                'mail_encryption' => 'tls',
                'mail_from_address' => 'noreply@csirt.gov',
                'mail_from_name' => 'CSIRT PALI'
            ],
            'notifications' => [
                'enable_email_notifications' => 'true',
                'enable_browser_notifications' => 'true',
                'notify_on_new_incident' => 'true',
                'notify_on_incident_update' => 'true',
                'notify_on_critical_incidents' => 'true',
                'notify_on_new_user_registration' => 'true',
                'notify_admins_on_contact' => 'true'
            ],
            'appearance' => [
                'theme' => 'default',
                'primary_color' => '#007bff',
                'secondary_color' => '#6c757d',
                'logo_url' => '',
                'favicon_url' => '',
                'items_per_page' => '15'
            ],
            'integrations' => [
                'enable_api' => 'true',
                'api_rate_limit' => '100',
                'enable_webhooks' => 'false',
                'webhook_secret' => '',
                'external_threat_feeds' => 'false'
            ]
        ];
    }

    /**
     * Get public settings that can be accessed by frontend
     */
    private function getPublicSettings()
    {
        return [
            'organization_name',
            'organization_description',
            'organization_mission',
            'organization_vision',
            'organization_address',
            'organization_phone',
            'organization_email',
            'timezone',
            'language',
            'theme',
            'primary_color',
            'secondary_color',
            'logo_url',
            'favicon_url'
        ];
    }

    /**
     * Guess setting type based on value
     */
    private function guessSettingType($value)
    {
        if (in_array(strtolower($value), ['true', 'false'])) {
            return 'boolean';
        }
        
        if (is_numeric($value)) {
            return 'number';
        }
        
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        }
        
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return 'url';
        }
        
        return 'text';
    }

    /**
     * Guess setting group based on key
     */
    private function guessSettingGroup($key)
    {
        if (str_contains($key, 'mail_') || str_contains($key, 'email_')) {
            return 'email';
        }
        
        if (str_contains($key, 'notify_') || str_contains($key, 'notification_')) {
            return 'notifications';
        }
        
        if (str_contains($key, 'password_') || str_contains($key, 'security_') || str_contains($key, 'auth_')) {
            return 'security';
        }
        
        if (str_contains($key, 'theme_') || str_contains($key, 'color_') || str_contains($key, 'logo_')) {
            return 'appearance';
        }
        
        if (str_contains($key, 'api_') || str_contains($key, 'webhook_') || str_contains($key, 'integration_')) {
            return 'integrations';
        }
        
        return 'general';
    }
}