<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhatsAppAccount;
use App\Models\WhatsappSecurityLog;
use App\Services\WhatsApp\DeveloperPasscodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DeveloperSecurityController extends Controller
{
    public function unlock(Request $request, DeveloperPasscodeService $passcodeService): JsonResponse
    {
        $this->authorize('whatsapp.manage');

        $validated = $request->validate([
            'passcode' => ['required', 'string', 'max:255'],
        ]);

        $ok = $passcodeService->verifyAndUnlock($request, $validated['passcode']);

        if (!$ok) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid developer passcode.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Developer settings unlocked for 15 minutes.',
        ]);
    }

    public function lock(Request $request, DeveloperPasscodeService $passcodeService): JsonResponse
    {
        $this->authorize('whatsapp.manage');

        $passcodeService->clearUnlock($request);
        $passcodeService->log($request, 'manual_lock', 'success');

        return response()->json([
            'success' => true,
            'message' => 'Developer settings relocked.',
        ]);
    }

    public function sensitiveData(Request $request, DeveloperPasscodeService $passcodeService): JsonResponse
    {
        $this->authorize('whatsapp.manage');

        if (!$passcodeService->isUnlocked($request)) {
            $passcodeService->log($request, 'sensitive_data_access', 'blocked');

            return response()->json([
                'success' => false,
                'message' => 'Developer access required.',
            ], 403);
        }

        $activeAccount = WhatsAppAccount::query()->latest('id')->first();

        if (!$activeAccount) {
            return response()->json([
                'success' => false,
                'message' => 'No active WhatsApp account found.',
            ], 404);
        }

        $passcodeService->log($request, 'sensitive_data_access', 'success');

        return response()->json([
            'success' => true,
            'data' => [
                'phone_number_id' => (string) $activeAccount->phone_number_id,
                'business_account_id' => (string) ($activeAccount->waba_id ?? ''),
                'waba_id' => (string) ($activeAccount->waba_id ?? ''),
                'status' => (string) $activeAccount->status,
                'access_token' => (string) $activeAccount->access_token,
                'webhook_verify_token' => (string) $activeAccount->webhook_verify_token,
            ],
        ]);
    }

    public function saveSensitive(Request $request, DeveloperPasscodeService $passcodeService): RedirectResponse
    {
        $this->authorize('whatsapp.manage');

        if (!$passcodeService->isUnlocked($request)) {
            $passcodeService->log($request, 'sensitive_edit', 'blocked');

            return back()->withErrors(['whatsapp_security' => 'Developer Access Required.'])->withInput();
        }

        $activeAccount = WhatsAppAccount::query()->latest('id')->first();

        if (!$activeAccount) {
            return back()->withErrors(['whatsapp_security' => 'No active WhatsApp account found.'])->withInput();
        }

        $validated = $request->validate([
            'phone_number_id' => ['required', 'string', 'max:80'],
            'business_account_id' => ['nullable', 'string', 'max:80'],
            'waba_id' => ['nullable', 'string', 'max:80'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'access_token' => ['required', 'string'],
            'webhook_verify_token' => ['required', 'string', 'max:255'],
        ]);

        $wabaId = $validated['waba_id'] ?: ($validated['business_account_id'] ?? null);

        $activeAccount->update([
            'phone_number_id' => $validated['phone_number_id'],
            'waba_id' => $wabaId,
            'status' => $validated['status'],
            'access_token' => $validated['access_token'],
            'webhook_verify_token' => $validated['webhook_verify_token'],
        ]);

        $passcodeService->log($request, 'sensitive_edit', 'success');

        return back()->with('success', 'Sensitive WhatsApp settings saved.');
    }

    public function index(Request $request, DeveloperPasscodeService $passcodeService): View
    {
        $this->authorize('whatsapp.view');

        $this->authorizeDeveloperAccess($request, $passcodeService);

        return view('admin.whatsapp.developer-security', [
            'logs' => WhatsappSecurityLog::query()->with('user')->orderByDesc('id')->paginate(20),
        ]);
    }

    public function updatePasscode(Request $request, DeveloperPasscodeService $passcodeService): RedirectResponse
    {
        $this->authorize('whatsapp.manage');

        $this->authorizeDeveloperAccess($request, $passcodeService);

        $validated = $request->validate([
            'master_passcode' => ['required', 'string', 'max:255'],
            'new_passcode' => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
        ]);

        if (!$passcodeService->verifyMasterPasscode($request, $validated['master_passcode'])) {
            return back()->withErrors(['master_passcode' => 'Invalid Master Passcode']);
        }

        $passcodeService->updatePasscode($request, $validated['new_passcode']);

        return back()->with('success', 'Developer passcode changed successfully.');
    }

    public function resetPasscode(Request $request, DeveloperPasscodeService $passcodeService): RedirectResponse
    {
        $this->authorize('whatsapp.manage');

        $this->authorizeDeveloperAccess($request, $passcodeService);

        $passcodeService->resetToDefaultPasscode($request);

        return back()->with('success', 'Developer passcode reset to default.');
    }

    public function forceRelock(Request $request, DeveloperPasscodeService $passcodeService): RedirectResponse
    {
        $this->authorize('whatsapp.manage');

        $this->authorizeDeveloperAccess($request, $passcodeService);

        $passcodeService->forceRelockAllSessions($request);

        return back()->with('success', 'All active unlock sessions have been relocked.');
    }

    private function authorizeDeveloperAccess(Request $request, DeveloperPasscodeService $passcodeService): void
    {
        if (!$passcodeService->isDeveloperManager($request->user())) {
            abort(403, 'Developer-only area.');
        }
    }
}
