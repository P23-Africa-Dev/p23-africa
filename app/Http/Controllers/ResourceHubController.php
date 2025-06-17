<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResourceHubController extends Controller
{
    public function resourceHub(){
        return view('resource-hub');
    }

    public function resourceDetails(){
        return view('view-resource');
    }
}
