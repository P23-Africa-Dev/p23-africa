<?php

namespace App\Http\Controllers;

use App\Helpers\ClickTracker;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AfricanExpansionGuideController extends Controller
{
    public function __invoke(): BinaryFileResponse
    {
        ClickTracker::track('African_Expansion_Guide_Download', 'African Expansion Guide PDF');

        $path = public_path('documents/African.pdf');

        abort_unless(is_file($path), 404);

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="African-Expansion-for-Manufacturers.pdf"',
        ]);
    }
}
