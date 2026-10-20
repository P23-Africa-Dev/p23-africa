<?php

namespace Tests\Feature;

use App\Support\CaseStudies;
use Tests\TestCase;

class CaseStudyPagesTest extends TestCase
{
    public function test_default_case_study_keeps_the_existing_thermoflow_content(): void
    {
        $this->get('/case-studies')
            ->assertOk()
            ->assertViewHas('caseStudy', fn (array $study) => $study['slug'] === 'thermoflow-hvac')
            ->assertSee('47 qualified buyer signals generated')
            ->assertSee('Distributor B');
    }

    public function test_every_json_entry_has_a_working_detail_page_and_matching_content(): void
    {
        foreach (CaseStudies::all() as $study) {
            $response = $this->get('/case-studies/'.$study['slug']);
            $response->assertOk()
                ->assertSee($study['client'])
                ->assertSee($study['challenge'])
                ->assertSee($study['outcome'])
                ->assertViewHas('caseStudy', fn (array $selected) => $selected['slug'] === $study['slug']);

            if ($study['illustrative']) {
                $response->assertSee('Illustrative example — sample content, not a client result.');
            }
        }
    }

    public function test_about_cards_link_to_the_matching_json_entries(): void
    {
        $studies = CaseStudies::all();
        $view = $this->view('about', ['caseStudies' => $studies]);

        foreach ($studies as $study) {
            $view->assertSee(route('case-studies.show', $study['slug']), false);
        }
        $view->assertSee(route('case-studies'), false);
        $view->assertSee(route('case-studies', ['all' => 1]).'#case-library', false);
    }

    public function test_unknown_case_studies_return_404(): void
    {
        $this->get('/case-studies/not-a-real-study')->assertNotFound();
    }

    public function test_case_study_navigation_wraps_and_view_all_opens_the_library(): void
    {
        $studies = CaseStudies::all();
        $response = $this->get('/case-studies?all=1')
            ->assertOk()
            ->assertViewHas('previousStudy', fn (array $study) => $study['slug'] === $studies[count($studies) - 1]['slug'])
            ->assertViewHas('nextStudy', fn (array $study) => $study['slug'] === $studies[1]['slug'])
            ->assertSee('Explore all case studies');

        $this->assertMatchesRegularExpression('/<details[^>]*id="case-library"[^>]*\sopen\s*>/', $response->getContent());
    }
}
