<?php

namespace App\Http\Controllers\Settings;

use App\Services\Settings\SettingService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\CreateSettingRequest;
use App\Http\Requests\Settings\UpdateSettingRequest;
use App\Http\Resources\Settings\SettingResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{    
    /**
     * @var SettingService
     */
    protected $settingService;
    
    /**
     * SettingController constructor
     *
     * @param  SettingService $settingService
     * @return void
     */
    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }
    
    /**
     * Get all settings
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $settings = $this->settingService->getSettings();

        return response()->json($settings);
    }
    
    /**
     * Create a setting
     *
     * @param  CreateSettingRequest $request
     * @return JsonResponse
     */
    public function store(CreateSettingRequest $request)
    {
        $data = $request->only(['name', 'value']);
        $setting = $this->settingService->createSetting($data);

        return new SettingResource($setting);
    }
    
    /**
     * Show a setting
     *
     * @param  $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $setting = $this->settingService->getSetting($id);

        return new SettingResource($setting);
    }

    public function update(UpdateSettingRequest $request, $id)
    {
        $setting = $this->settingService->getSetting($id);

        $data = $request->only(['name', 'value']);

        $setting = $this->settingService->updateSetting($setting, $data);

        return new SettingResource($setting);
    }
}
