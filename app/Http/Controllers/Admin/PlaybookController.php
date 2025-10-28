<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlaybookDownload;
use Illuminate\Support\Facades\Storage;

class PlaybookController extends Controller
{
    public function index()
    {
        $playbooks = PlaybookDownload::paginate(20);

        return view('admin.playbooks.index', compact('playbooks'));
    }

    public function destroy(PlaybookDownload $playbook)
    {
        if ($playbook->image_path) Storage::disk('public')->delete($playbook->image_path);
        $playbook->delete();
        return redirect()->route('admin.playbooks.index')->with('success', 'Playbook deleted.');
    }
}
