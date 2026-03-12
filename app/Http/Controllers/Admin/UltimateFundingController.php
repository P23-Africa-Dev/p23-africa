<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UltimateFundingDownload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UltimateFundingController extends Controller
{
    /**
     * Display a listing of the ultimate funding downloads.
     */
    public function index()
    {
        $ultimateFunding = UltimateFundingDownload::orderBy('created_at', 'desc')->paginate(20);

        return view('admin.ultimatefunding.index', compact('ultimateFunding'));
    }

    /**
     * Remove the specified ultimate funding download from storage.
     */
    public function destroy(UltimateFundingDownload $ultimatefunding)
    {
        $ultimatefunding->delete();
        return orgRedirect('admin.ultimatefunding.index')->with('success', 'Ultimate Funding download record deleted successfully.');
    }
}
