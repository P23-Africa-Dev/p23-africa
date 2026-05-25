<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SyncWhatsAppBusinessProfileJob;
use App\Models\WhatsAppAccount;
use App\Services\WhatsApp\WhatsAppBusinessProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MetaProfileSyncController extends Controller
{
    public function index(): View
    {
        $this->authorize('whatsapp.view');

        $account = WhatsAppAccount::query()->latest('id')->first();

        return view('admin.whatsapp.profile-sync', [
            'activeAccount' => $account,
        ]);
    }

    public function syncNow(): RedirectResponse
    {
        $this->authorize('whatsapp.manage');

        $account = WhatsAppAccount::query()->latest('id')->first();

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

    public function refreshMetaStatus(WhatsAppBusinessProfileService $service): RedirectResponse
    {
        $this->authorize('whatsapp.manage');

        $account = WhatsAppAccount::query()->latest('id')->first();

        if (! $account) {
            return back()->withErrors(['whatsapp_profile' => 'No active WhatsApp account is configured.']);
        }

        $service->refreshMetaStatus($account);

        return back()->with('success', 'Meta profile status refreshed successfully.');
    }
}
