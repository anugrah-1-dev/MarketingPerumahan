<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_wa_update_does_not_clear_social_media_settings(): void
    {
        Setting::set('instagram_url', 'https://instagram.com/bukitshangrilla');
        Setting::set('tiktok_url', 'https://tiktok.com/@bukitshangrilla');
        Setting::set('facebook_url', 'https://facebook.com/bukitshangrilla');

        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($user)->post(route('manager.settings.update'), [
            'wa_admin' => '6281234567890',
        ]);

        $response->assertRedirect(route('manager.settings'));

        $this->assertSame('6281234567890', Setting::get('wa_admin'));
        $this->assertSame('https://instagram.com/bukitshangrilla', Setting::get('instagram_url'));
        $this->assertSame('https://tiktok.com/@bukitshangrilla', Setting::get('tiktok_url'));
        $this->assertSame('https://facebook.com/bukitshangrilla', Setting::get('facebook_url'));
    }
}