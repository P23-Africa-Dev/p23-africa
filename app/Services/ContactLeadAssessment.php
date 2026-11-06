<?php

namespace App\Services;

class ContactLeadAssessment
{
    /**
     * Route a contact assessment by the budget the visitor entered (GBP).
     *
     * Route 3: below consultation_min            -> Resource Hub
     * Route 2: consultation_min - consultation_max -> paid consultation flow
     * Route 1: above consultation_max            -> discovery call scheduling
     */
    public function evaluate(array $answers): array
    {
        $budget = (float) ($answers['budget_range'] ?? 0);
        $routes = config('contact.budget_routes', []);
        $min = (float) ($routes['consultation_min'] ?? 1500);
        $max = (float) ($routes['consultation_max'] ?? 2500);

        if ($budget < $min) {
            return [
                'route' => 'resources', 'title' => 'Start with practical resources',
                'description' => 'Explore insights and guides to sharpen your plans before committing to a project.',
                'label' => 'Explore our services', 'url' => locale_route('resource-hub'),
            ];
        }

        if ($budget <= $max) {
            return [
                'route' => 'consultation', 'title' => 'Schedule your consultation',
                'description' => 'Pick a convenient date and time for your paid consultation session.',
                'label' => 'Schedule your consultation', 'url' => locale_route('contact.consultation.schedule'),
            ];
        }

        return [
            'route' => 'discovery', 'title' => 'Schedule your discovery call',
            'description' => 'Pick a convenient date and time for a discovery call with our team.',
            'label' => 'Schedule your discovery call', 'url' => locale_route('contact.discovery.schedule'),
        ];
    }
}
