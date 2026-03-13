<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;

class CrmSettingsController extends Controller
{
    /**
     * Resolve the Organization for the current context.
     * Returns null for the global super-admin context (no org).
     */
    protected function currentOrg(): ?Organization
    {
        $user = auth()->user();

        if ($user && !$user->hasRole('admin') && $user->organization_id) {
            return Organization::find($user->organization_id);
        }

        return null;
    }

    /**
     * Show CRM settings form.
     */
    public function index()
    {
        $org = $this->currentOrg();

        return view('admin.crm.settings', [
            'org'          => $org,
            'currencies'   => Organization::getCurrencyList(),
            'currentCode'  => $org ? $org->getCrmCurrency() : 'USD',
        ]);
    }

    /**
     * Save CRM settings (currency selection).
     */
    public function update(Request $request)
    {
        $org = $this->currentOrg();

        $validated = $request->validate([
            'crm_currency' => ['required', 'string', 'size:3', 'in:' . implode(',', array_keys(Organization::getCurrencyList()))],
        ]);

        if ($org) {
            $org->update(['crm_currency' => $validated['crm_currency']]);
        }

        return back()->with('success', 'CRM currency updated successfully.');
    }
}
