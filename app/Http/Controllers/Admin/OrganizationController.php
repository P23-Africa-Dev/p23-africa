<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrganizationController extends Controller
{
    public function index()
    {
        $orgs = Organization::orderBy('name')->get();
        return view('admin.organizations.index', compact('orgs'));
    }

    public function create()
    {
        return view('admin.organizations.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:organizations,name',
            'description' => 'nullable|string|max:1000',
        ]);

        $org = Organization::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
        ]);

        return orgRedirect('admin.organizations.index')->with('success', 'Organization created successfully.');
    }

    public function storeAjax(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:organizations,name',
        ]);

        $org = Organization::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
        ]);

        return response()->json(['success' => true, 'organization' => $org]);
    }

    /**
     * Show the form for editing the specified organization.
     */
    public function edit(Organization $organization)
    {
        return view('admin.organizations.edit', compact('organization'));
    }

    /**
     * Update the specified organization.
     */
    public function update(Request $request, Organization $organization)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('organizations')->ignore($organization->id)],
            'description' => 'nullable|string|max:1000',
        ]);

        $organization->update([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'] ?? $organization->description,
        ]);

        return orgRedirect('admin.organizations.index')->with('success', 'Organization updated successfully.');
    }

    /**
     * Remove the specified organization.
     */
    public function destroy(Organization $organization)
    {
        // Prevent deletion if organization has related data
        if ($organization->users()->count() > 0 || $organization->projects()->count() > 0 || $organization->leads()->count() > 0) {
            return orgRedirect('admin.organizations.index')->with('error', 'Cannot delete organization that has users, projects or leads.');
        }

        $organization->delete();

        return orgRedirect('admin.organizations.index')->with('success', 'Organization deleted successfully.');
    }
}
