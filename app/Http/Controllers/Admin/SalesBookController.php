<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalesBookDownload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SalesBookController extends Controller
{
    public function index()
    {
        $salesBooks = SalesBookDownload::orderBy('created_at', 'desc')->paginate(20);

        return view('admin.salesbooks.index', compact('salesBooks'));
    }

    public function destroy(SalesBookDownload $salesBook)
    {
        $salesBook->delete();
        return redirect()->route('admin.salesbooks.index')->with('success', 'Sales book download record deleted successfully.');
    }
}
