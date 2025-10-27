<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlaybookDownload;
use Illuminate\Http\Request;

class PlaybookController extends Controller
{
    public function index()
    {
        $playbooks = PlaybookDownload::paginate(20);

        return view('admin.playbooks.index', compact('playbooks'));
    }
}
