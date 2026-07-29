<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SyncWhatsAppBusinessProfileJob;
use App\Jobs\SyncWhatsAppTemplatesJob;
use App\Services\WhatsApp\WhatsAppAccountService;
use App\Services\WhatsApp\WhatsAppBusinessProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MetaProfileSyncController extends Controller
{
    public function index(Request $request, WhatsAppAccountService $accountService): View
    {
        $this->authorize('whatsapp.view');

        $account = $accountService->getActiveAccountForUser($request->user());

        return view('admin.whatsapp.profile-sync', [
            'activeAccount' => $account,
        ]);
    }

    public function syncNow(Request $request, WhatsAppAccountService $accountService): RedirectResponse
    {
        $this->authorize('whatsapp.manage');

        $account = $accountService->getActiveAccountForUser($request->user());

        if (! $account) {
            return back()->withErrors(['whatsapp_profile' => 'No active WhatsApp account is configured.']);
        }

        $account->forceFill([
            'profile_sync_status' => WhatsAppBusinessProfileService::STATUS_PENDING_APPROVAL,
            'display_name_status' => WhatsAppBusinessProfileService::STATUS_PENDING_APPROVAL,
            'last_profile_sync' => now(),
        ])->save();

        SyncWhatsAppBusinessProfileJob::dispatch($account->id)->onQueue('whatsapp');

        return back()->with('success', 'WhatsApp business profile sync queued for Meta review.');
    }

    public function refreshMetaStatus(
        Request $request,
        WhatsAppBusinessProfileService $service,
        WhatsAppAccountService $accountService
    ): RedirectResponse
    {
        $this->authorize('whatsapp.manage');

        $account = $accountService->getActiveAccountForUser($request->user());

        if (! $account) {
            return back()->withErrors(['whatsapp_profile' => 'No active WhatsApp account is configured.']);
        }

        $service->refreshMetaStatus($account);

        return back()->with('success', 'Meta profile status refreshed successfully.');
    }

    public function syncTemplatesNow(Request $request, WhatsAppAccountService $accountService): RedirectResponse
    {
        $this->authorize('whatsapp.manage');

        $account = $accountService->getActiveAccountForUser($request->user());

        if (!$account) {
            return back()->withErrors(['whatsapp_templates' => 'No active WhatsApp account is configured.']);
        }

        SyncWhatsAppTemplatesJob::dispatch($account->id)->onQueue('whatsapp');

        return back()->with('success', 'WhatsApp template sync queued successfully.');
    }
}
