<?php

namespace App\Services\Settings;

use App\Models\Settings\Setting;
use App\Services\Service;
use Illuminate\Support\Collection;

class SettingService extends Service
{  
  /**
   * @var Setting
   */
  protected $setting;
  
  /**
   * SettingService constructor
   *
   * @param  Setting $setting
   * @return void
   */
  public function __construct(Setting $setting)
  {
    parent::__construct();

    $this->setting = $setting;
  }
  
  /**
   * Get settings
   *
   * @return Collection
   */
  public function getSettings()
  {
    $settings = $this->setting::select('id', 'name', 'value')
      ->orderBy('id', 'desc')
      ->get();

    return $settings;
  }
  
  /**
   * Create a setting
   *
   * @param  array $data
   * @return Setting
   */
  public function createSetting(array $data)
  {
    $setting = $this->setting::create($data);

    return $setting;
  }
  
  /**
   * Get a setting
   *
   * @param  $id
   * @return Setting
   */
  public function getSetting($id)
  {
    $setting = $this->setting::findOrFail($id);

    return $setting;
  }
  
  /**
   * Update a setting
   *
   * @param  Setting $setting
   * @param  array $data
   * @return Setting
   */
  public function updateSetting(Setting $setting, array $data)
  {
    $setting->update($data);

    return $setting;
  }
}