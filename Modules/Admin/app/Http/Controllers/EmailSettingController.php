<?php

namespace Modules\Admin\Http\Controllers;

use Modules\Admin\Http\Requests\EmailSettingRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Common\Services\EmailSettingService;


class EmailSettingController extends Controller
{
    protected $service;

    public function __construct(EmailSettingService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $settings = $this->service->getAll();
        $active   = $this->service->getActive();

        return view('admin::email-settings.index', compact('settings', 'active'));
    }

    public function create()
    {
        return view('admin::email-settings.create');
    }

    public function store(EmailSettingRequest $request)
    {
        $this->service->create($request->validated());

        return redirect()->route('admin.email-settings.index')
            ->with('success', 'Email setting created successfully.');
    }

    public function edit(int $id)
    {
        $setting = $this->service->findById($id);
        return view('admin::email-settings.edit', compact('setting'));
    }

    public function update(EmailSettingRequest $request, int $id)
    {
        $this->service->update($id, $request->validated());

        return redirect()->route('admin.email-settings.index')
            ->with('success', 'Email setting updated successfully.');
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);
        return redirect()->route('admin.email-settings.index')
            ->with('success', 'Email setting deleted.');
    }

    public function setActive(int $id)
    {
        $this->service->setActive($id);
        return back()->with('success', 'Email setting set as active.');
    }

    public function writeToEnv(int $id)
    {
        try {
            $this->service->writeToEnv($id);
            return back()->with('success', 'Email settings saved to .env file and config cleared.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to write to .env: ' . $e->getMessage());
        }
    }

    public function testEmail(EmailSettingRequest $request, int $id)
    {
        $result = $this->service->sendTestEmail($id, $request->test_email);

        return back()->with(
            $result['success'] ? 'success' : 'error',
            $result['message']
        );
    }
}
