<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Event;
use App\Support\CaseStudies;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $paths = [
            '/',
            '/about',
            '/services',
            '/market-entry',
            '/market-entry/nigeria',
            '/market-entry/ghana',
            '/distributor-partner-development',
            '/outbound-sales-demand-testing',
            '/contact',
            '/case-studies',
            '/events',
            '/all-events',
            '/resource-hub',
            '/res/archive',
            '/res/archive/list',
            '/noel54',
            '/noel54/interest-form',
            '/student',
            '/founders-playbook',
            '/playbook/funding-readiness',
            '/playbook/ultimate-funding',
            '/quiz',
            '/quiz/start',
            '/quiz/result/fragile-funnel',
            '/quiz/result/hidden-powerhouse',
            '/quiz/result/cloudy-climber',
            '/book-consultation/form',
            '/privacy-policy',
            '/terms-and-conditions',
        ];

        foreach (CaseStudies::all() as $study) {
            if (!empty($study['slug'])) {
                $paths[] = '/case-studies/'.$study['slug'];
            }
        }

        foreach ($this->values(Blog::class, 'slug') as $slug) {
            $paths[] = '/resource/'.$slug;
        }

        foreach ($this->values(Event::class, 'slug') as $slug) {
            $paths[] = '/events/'.$slug;
        }

        foreach ($this->values(Category::class, 'name') as $name) {
            if (preg_match('/^[A-Za-z0-9\-]+$/', $name)) {
                $paths[] = '/res/category/'.$name;
            }
        }

        $paths = array_values(array_unique($paths));
        $today = now()->toDateString();
        $xml = ['<?xml version="1.0" encoding="UTF-8"?>'];
        $xml[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($paths as $path) {
            foreach (['', 'fr', 'de', 'nl'] as $locale) {
                $location = $locale === ''
                    ? url($path)
                    : url('/'.$locale.($path === '/' ? '' : $path));

                $xml[] = '<url><loc>'.e($location).'</loc><lastmod>'.$today.'</lastmod></url>';
            }
        }

        $xml[] = '</urlset>';

        return response(implode("\n", $xml), 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }

    /**
     * @param  class-string  $model
     * @return array<int, string>
     */
    private function values(string $model, string $column): array
    {
        try {
            return $model::query()
                ->whereNotNull($column)
                ->pluck($column)
                ->filter(fn ($value) => is_string($value) && $value !== '')
                ->values()
                ->all();
        } catch (\Throwable $e) {
            report($e);

            return [];
        }
    }
}
