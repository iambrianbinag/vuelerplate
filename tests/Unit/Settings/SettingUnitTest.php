<?php

namespace Tests\Unit\Settings;

use App\Models\Settings\Setting;
use App\Services\Settings\SettingService;
use Tests\TestCase;

class SettingUnitTest extends TestCase
{
    /** @test */
    public function it_can_get_a_setting_by_name()
    {
        $nameKey = 'name';
        $nameValue = 'Vuelerplate';

        Setting::factory()->create(['name' => $nameKey, 'value' => $nameValue]);

        $settingService = app()->make(SettingService::class);
        $foundSetting = $settingService->getSettingByName($nameKey);

        $this->assertInstanceOf(Setting::class, $foundSetting);
        $this->assertEquals($nameValue, $foundSetting->value);
    }
}
