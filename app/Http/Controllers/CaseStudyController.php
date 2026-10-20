<?php

namespace App\Http\Controllers;

use App\Support\CaseStudies;
use Illuminate\View\View;

class CaseStudyController extends Controller
{
    public function show(?string $slug = null): View
    {
        $caseStudies = CaseStudies::all();
        $index = $slug === null ? 0 : array_search($slug, array_column($caseStudies, 'slug'), true);
        abort_if($index === false, 404);

        $count = count($caseStudies);

        return view('case-studies', [
            'caseStudy' => $caseStudies[$index],
            'caseStudies' => $caseStudies,
            'previousStudy' => $caseStudies[($index + $count - 1) % $count],
            'nextStudy' => $caseStudies[($index + 1) % $count],
        ]);
    }
}
