@extends('layouts.master')
@section('title', 'Outbound Demand Testing for Africa | Test the Market Before You Commit Capital')
@section('body_class', 'p23-black-navbar-page sd-body')

@section('meta')
    <meta name="description"
        content="Test real buyer demand with prospective customers in Africa. Validate commercial pipeline and buying interest before opening an office or hiring teams.">
    <meta property="og:title" content="Outbound Demand Testing for Africa | Test the Market Before You Commit Capital">
    <meta property="og:description" content="Test real buyer demand with prospective customers in Africa. Validate commercial pipeline and buying interest before opening an office or hiring teams.">
    <meta property="og:image" content="{{ asset('images/services/sd-outbound-hero.jpg') }}">
    <link rel="canonical" href="{{ url('services/outbound-demand-testing') }}">
    {{-- services-new.css carries the shared footer3 + navbar styles used across the service pages --}}
    <link rel="stylesheet" href="{{ asset('css/services-new.css') }}">
    <link rel="stylesheet" href="{{ asset('css/service-detail.css') }}">

    <script type="application/ld+json">
    {!! json_encode([
      '@context' => 'https://schema.org',
      '@graph' => [
        [
          '@type' => 'Service',
          'name' => 'Outbound Demand Testing for Africa',
          'serviceType' => 'Outbound Sales & Commercial Demand Validation',
          'provider' => [
            '@type' => 'Organization',
            'name' => 'P23 Africa',
            'url' => 'https://p23africa.com'
          ],
          'url' => url('services/outbound-demand-testing'),
          'description' => 'Test real buyer demand with prospective customers in Africa. Validate commercial pipeline and buying interest before opening an office or hiring teams.'
        ],
        [
          '@type' => 'BreadcrumbList',
          'itemListElement' => [
            [
              '@type' => 'ListItem',
              'position' => 1,
              'name' => 'Home',
              'item' => url('/')
            ],
            [
              '@type' => 'ListItem',
              'position' => 2,
              'name' => 'Services',
              'item' => url('services')
            ],
            [
              '@type' => 'ListItem',
              'position' => 3,
              'name' => 'Outbound Demand Testing',
              'item' => url('services/outbound-demand-testing')
            ]
          ]
        ]
      ]
    ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
@endsection

@section('navbar')
    @include('layouts.includes.navbar-v2', ['theme' => 'dark', 'active' => 'what-we-do'])
@endsection

@php
    $check = '<svg viewBox="196 2846 21 20" fill="none" aria-hidden="true" focusable="false"><path opacity=".1" d="M206.358 2846C200.656 2846 196.033 2850.48 196.033 2856C196.033 2861.52 200.656 2866 206.358 2866C212.061 2866 216.684 2861.52 216.684 2856C216.677 2850.48 212.058 2846.01 206.358 2846Z" fill="#fff"/><path d="M211.811 2852.83L205.921 2860.57C205.781 2860.75 205.571 2860.87 205.34 2860.9C205.109 2860.93 204.875 2860.87 204.691 2860.73L200.485 2857.47C200.114 2857.19 200.054 2856.66 200.351 2856.3C200.648 2855.94 201.189 2855.89 201.561 2856.17L205.068 2858.89L210.426 2851.85C210.601 2851.59 210.908 2851.45 211.223 2851.48C211.539 2851.51 211.812 2851.7 211.935 2851.99C212.058 2852.27 212.01 2852.59 211.811 2852.83Z" fill="#fff"/></svg>';

    $faqs = [
        ['q' => __('service_detail.outbound.faq_1_q'), 'a' => __('service_detail.outbound.faq_1_a')],
        ['q' => __('service_detail.outbound.faq_2_q'), 'a' => __('service_detail.outbound.faq_2_a')],
        ['q' => __('service_detail.outbound.faq_3_q'), 'a' => __('service_detail.outbound.faq_3_a')],
        ['q' => __('service_detail.outbound.faq_4_q'), 'a' => __('service_detail.outbound.faq_4_a')],
        ['q' => __('service_detail.outbound.faq_5_q'), 'a' => __('service_detail.outbound.faq_5_a')],
    ];
    $openFaq = 4;
@endphp

@section('content')
<div class="sd-page sd-page--outbound">

    {{-- ==========================================
         HERO
         ========================================== --}}
    <section class="sd-hero" id="hero" style="--hero-h: 536; --btn-gap: 55;">
        <div class="sd-inner">
            <div class="sd-hero__content">
                <h1 class="sd-hero__title">
                    {!! __('service_detail.outbound.hero') !!}
                </h1>
                <p class="sd-hero__desc">
                    {{ __('service_detail.outbound.hero_desc') }}
                </p>
                <a href="{{ locale_route('contact') }}" class="sd-hero__btn" id="sd-hero-scoping-btn">{{ __('service_detail.outbound.book_call') }}</a>
            </div>

            <div class="sd-hero__visual" style="--ox: 791; --vw: 666; --vh: 536; --ar: 666 / 536;">
                <div class="sd-hero__img" style="--x: 791; --y: 0; --w: 666; --h: 536; --bg: #D9D9D9;">
                    <img src="{{ asset('images/services/sd-outbound-hero.jpg') }}" alt="{{ __('service_detail.outbound.hero_alt') }}" loading="eager" fetchpriority="high">
                </div>
            </div>
        </div>
    </section>

    {{-- ==========================================
         HOW IT WORKS (BENTO)
         ========================================== --}}
    <section class="sd-stage" id="how-it-works" style="--stage-gap: 103;">
        <div class="sd-inner">
            <div class="sd-bento-o">
                <div class="sd-bento-o__col">
                    {{-- 1 --}}
                    <article class="sd-card" style="--h: 202; --bg: #283749; --pt: 29.5; --pl: 39; --pb: 28; --mw: 345;">
                        <span class="sd-num" style="--x: 375; --y: 22;" aria-hidden="true">1</span>
                        <div class="sd-card__inner">
                            <h2 class="sd-card__lead">{{ __('service_detail.outbound.how_it_works') }}</h2>
                            <h3 class="sd-card-title sd-gap-lg" style="margin-top: calc(37.5 * var(--u));">{{ __('service_detail.outbound.define_title') }}</h3>
                            <p class="sd-card-text sd-gap-8">{{ __('service_detail.outbound.define_text') }}</p>
                        </div>
                    </article>

                    {{-- 2 --}}
                    <article class="sd-card" style="--h: 455; --bg: #FAF4F0; --fg: #283749; --pt: 24; --pl: 26; --pb: 16; --mw: 400;">
                        <span class="sd-num" style="--x: 362; --y: 9; --stroke: #283749;" aria-hidden="true">2</span>
                        <div class="sd-card__inner">
                            <h3 class="sd-card-title">{!! __('service_detail.outbound.find_title') !!}</h3>
                            <div class="sd-card-text sd-gap-20">
                                <p>{{ __('service_detail.outbound.find_intro') }}</p>
                                <ul class="sd-list">
                                    <li>{{ __('service_detail.outbound.find_hiring') }}</li>
                                    <li>{{ __('service_detail.outbound.find_expansion') }}</li>
                                    <li>{{ __('service_detail.outbound.find_tenders') }}</li>
                                    <li>{{ __('service_detail.outbound.find_regulatory') }}</li>
                                    <li>{{ __('service_detail.outbound.find_shows') }}</li>
                                </ul>
                                <p>{{ __('service_detail.outbound.find_source') }}</p>
                            </div>
                        </div>
                    </article>
                </div>

                {{-- 3 --}}
                <article class="sd-card" style="--h: 676; --bg: #390908; --pt: 142; --pl: 36; --pb: 40; --mw: 345;">
                    <span class="sd-num" style="--x: 348; --y: 93;" aria-hidden="true">3</span>
                    <div class="sd-card__inner">
                        <h3 class="sd-card-title">{!! __('service_detail.outbound.outreach_title') !!}</h3>
                        <div class="sd-card-text sd-gap-20">
                            <ul class="sd-list">
                                <li>{!! __('service_detail.outbound.outreach_calls') !!}</li>
                                <li>{!! __('service_detail.outbound.outreach_email') !!}</li>
                                <li>{!! __('service_detail.outbound.outreach_linkedin') !!}</li>
                                <li>{!! __('service_detail.outbound.outreach_sms') !!}</li>
                                <li>{!! __('service_detail.outbound.outreach_in_person') !!}</li>
                            </ul>
                            <p>{{ __('service_detail.outbound.outreach_close') }}</p>
                        </div>
                    </div>
                </article>

                {{-- 4 --}}
                <article class="sd-card" style="--h: 479; --bg: #587C90; --pt: 135; --pl: 15; --pr: 16; --pb: 40; --mw: 335;">
                    <span class="sd-num" style="--x: 294; --y: 32;" aria-hidden="true">4</span>
                    <div class="sd-card__inner">
                        <h3 class="sd-card-title">{{ __('service_detail.outbound.response_title') }}</h3>
                        <ul class="sd-list sd-card-text sd-gap-8">
                            <li>{!! __('service_detail.outbound.response_interest') !!}</li>
                            <li>{!! __('service_detail.outbound.response_objections') !!}</li>
                            <li>{!! __('service_detail.outbound.response_silence') !!}</li>
                        </ul>
                    </div>
                </article>
            </div>
        </div>
    </section>

    {{-- ==========================================
         WHAT YOU GET + PRICE
         ========================================== --}}
    <section class="sd-get sd-get--o" id="what-you-get" style="--get-gap: 32;">
        <div class="sd-get__band">
            <div class="sd-inner">
                <div class="sd-get__intro">
                    <h2 class="sd-get__title">{{ __('service_detail.outbound.get_title') }}</h2>
                    <p class="sd-get__sub">{!! __('service_detail.outbound.get_sub') !!}</p>

                    <ul class="sd-pills sd-pills--stack">
                        <li class="sd-pill" style="--w: 369; --pl: 48.75;">{{ __('service_detail.outbound.pill_profile') }}</li>
                        <li class="sd-pill" style="--w: 430; --pl: 47.75;">{{ __('service_detail.outbound.pill_list') }}</li>
                        <li class="sd-pill" style="--w: 369; --pl: 46.25;">{{ __('service_detail.outbound.pill_campaign') }}</li>
                        <li class="sd-pill" style="--w: 441; --pl: 44.75;">{{ __('service_detail.outbound.pill_logged') }}</li>
                        <li class="sd-pill" style="--w: 584; --pl: 43.75;">{{ __('service_detail.outbound.pill_report') }}</li>
                        <li class="sd-pill" style="--w: 369; --pl: 39.25;">{{ __('service_detail.outbound.pill_recommendation') }}</li>
                    </ul>
                </div>

                <article class="sd-price" id="pricing" style="--h: 619;">
                    <span class="sd-price__badge">{{ __('service_detail.outbound.price_badge') }}</span>
                    <div class="sd-price__row">
                        <span class="sd-price__amount">£3,500</span>
                    </div>
                    <h3 class="sd-price__included">{{ __('service_detail.outbound.included') }}</h3>
                    <ul class="sd-checks">
                        <li>{!! $check !!}{{ __('service_detail.outbound.check_country') }}</li>
                        <li>{!! $check !!}{{ __('service_detail.outbound.check_segment') }}</li>
                        <li>{!! $check !!}{{ __('service_detail.outbound.check_research') }}</li>
                        <li>{!! $check !!}{{ __('service_detail.outbound.check_targets') }}</li>
                        <li>{!! $check !!}{{ __('service_detail.outbound.check_campaign') }}</li>
                        <li>{!! $check !!}{{ __('service_detail.outbound.check_followup') }}</li>
                        <li>{!! $check !!}{{ __('service_detail.outbound.check_tracking') }}</li>
                        <li>{!! $check !!}{{ __('service_detail.outbound.check_assessment') }}</li>
                        <li>{!! $check !!}{{ __('service_detail.outbound.check_delivery') }}</li>
                    </ul>
                </article>
            </div>
        </div>
    </section>

    {{-- ==========================================
         CASE STUDIES
         ========================================== --}}
    <section class="sd-cases" id="case-studies">
        <div class="sd-inner">
            <div class="sd-cases__stack">
                <a href="{{ locale_route('case-studies.show', ['slug' => 'thermoflow-hvac']) }}" class="sd-case">
                    <img src="{{ asset('images/case-studies/thermoflow.png') }}" alt="" loading="lazy">
                    <h3 class="sd-case__name">Thermoflow HVAC</h3>
                    <p class="sd-case__desc">{{ __('service_detail.outbound.case_thermoflow') }}</p>
                    <span class="sd-case__more">{{ __('service_detail.outbound.continue') }}</span>
                </a>
                <a href="{{ locale_route('case-studies.show', ['slug' => 'coolair-systems']) }}" class="sd-case">
                    <img src="{{ asset('images/case-studies/coolair.png') }}" alt="" loading="lazy">
                    <h3 class="sd-case__name">Coolair Systems</h3>
                    <p class="sd-case__desc">{{ __('service_detail.outbound.case_coolair') }}</p>
                    <span class="sd-case__more">{{ __('service_detail.outbound.continue') }}</span>
                </a>
                <a href="{{ locale_route('case-studies.show', ['slug' => 'packline-industrial-machinery']) }}" class="sd-case">
                    <img src="{{ asset('images/case-studies/packline.png') }}" alt="" loading="lazy">
                    <h3 class="sd-case__name">Packline Industrial Machinery Inc.</h3>
                    <p class="sd-case__desc">{{ __('service_detail.outbound.case_packline') }}</p>
                    <span class="sd-case__more">{{ __('service_detail.outbound.continue') }}</span>
                </a>
            </div>

            <div class="sd-cases__mid">
                <h2 class="sd-cases__title">{!! __('service_detail.outbound.cases_title') !!}</h2>
                <a href="{{ locale_route('case-studies.show', ['slug' => 'cmppower']) }}" class="sd-dotlink">{{ __('service_detail.outbound.read_full') }}</a>
                <a href="{{ locale_route('case-studies.show', ['slug' => 'cmppower']) }}" class="sd-case" style="--h: 291; --pl: 17; --pb: 24;">
                    <img src="{{ asset('images/case-studies/liftmasters.png') }}" alt="" loading="lazy">
                    <h3 class="sd-case__name">CmpPower</h3>
                    <p class="sd-case__desc">{{ __('service_detail.outbound.case_cmp') }}</p>
                    <span class="sd-case__more">{{ __('service_detail.outbound.continue') }}</span>
                </a>
            </div>

            <div class="sd-cases__aside">
                <a href="{{ locale_route('case-studies') }}" class="sd-dotlink">{{ __('service_detail.outbound.read_full') }}</a>
                <h2 class="sd-cases__title">{!! __('service_detail.outbound.what_title') !!}</h2>
                <p>{!! __('service_detail.outbound.what_body') !!}</p>
            </div>
        </div>
    </section>

    {{-- ==========================================
         COMMON QUESTIONS
         ========================================== --}}
    <section class="sd-faq" id="faq">
        <div class="sd-inner">
            <div class="sd-faq__box">
                <div class="sd-faq__media">
                    <img src="{{ asset('images/services/sd-faq-advisor.jpg') }}" alt="{{ __('service_detail.outbound.faq_alt') }}" loading="lazy">
                </div>

                <div class="sd-faq__list">
                    <h2 class="sd-faq__title">{{ __('service_detail.outbound.faq_title') }}</h2>
                    @foreach ($faqs as $i => $faq)
                        <div class="sd-faq__item {{ $i === $openFaq ? 'is-open' : '' }}">
                            <button type="button" class="sd-faq__q" id="sd-faq-q-{{ $i }}"
                                    aria-expanded="{{ $i === $openFaq ? 'true' : 'false' }}"
                                    aria-controls="sd-faq-a-{{ $i }}">
                                <span>{{ $faq['q'] }}</span>
                                <span class="sd-faq__icon" aria-hidden="true"></span>
                            </button>
                            <div class="sd-faq__a" id="sd-faq-a-{{ $i }}" role="region" aria-labelledby="sd-faq-q-{{ $i }}">
                                <div><p>{{ $faq['a'] }}</p></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ==========================================
         CTA
         ========================================== --}}
    <section class="sd-cta" id="cta">
        <div class="sd-inner">
            <div class="sd-cta__content">
                <h2 class="sd-cta__title">{{ __('service_detail.outbound.cta_title') }}</h2>
                <p class="sd-cta__desc">{{ __('service_detail.outbound.cta_desc') }}</p>
                <div class="sd-cta__actions">
                    <a href="{{ locale_route('contact') }}" class="sd-cta__btn" id="sd-cta-scoping-btn">{{ __('service_detail.outbound.book_call') }}</a>
                    <div class="sd-cta__alt">
                        <span>{{ __('service_detail.outbound.cta_known') }}</span>
                        <a href="{{ locale_route('services.distributor-partner') }}">{{ __('service_detail.outbound.cta_partner') }}</a>
                    </div>
                </div>
            </div>

            <div class="sd-cta__widget">
                <svg viewBox="936 3684 377 354" fill="none" role="img" aria-label="{{ __('service_detail.outbound.chart_label') }}">
                    <rect x="936" y="3684" width="377" height="354" rx="24" fill="#000"/>
                    <path d="M1084.93 3736.06C1084.93 3733.25 1087.21 3730.96 1090.02 3731.07C1110.46 3731.86 1130.46 3737.4 1148.42 3747.27C1167.87 3757.97 1184.31 3773.4 1196.2 3792.14C1208.09 3810.88 1215.06 3832.33 1216.46 3854.48C1217.74 3874.94 1214.24 3895.39 1206.25 3914.22C1205.15 3916.81 1202.11 3917.9 1199.57 3916.7L1156.31 3896.35C1153.77 3895.15 1152.69 3892.12 1153.71 3889.5C1157.59 3879.54 1159.26 3868.83 1158.58 3858.12C1157.8 3845.72 1153.9 3833.71 1147.24 3823.21C1140.58 3812.72 1131.38 3804.07 1120.48 3798.08C1111.08 3792.92 1100.68 3789.87 1090.02 3789.13C1087.21 3788.94 1084.93 3786.68 1084.93 3783.87V3736.06Z" fill="#9F8965" stroke="#000" stroke-width="3.71429" stroke-linejoin="round"/>
                    <path d="M1199.63 3916.58C1202.17 3917.77 1203.27 3920.81 1201.98 3923.31C1190.98 3944.58 1174.37 3962.48 1153.91 3975.05C1132 3988.51 1106.63 3995.26 1080.93 3994.48C1055.23 3993.7 1030.32 3985.42 1009.27 3970.66C989.608 3956.87 974.114 3938 964.421 3916.1C963.283 3913.52 964.569 3910.56 967.182 3909.52L1011.61 3891.88C1014.22 3890.84 1017.17 3892.13 1018.38 3894.66C1023.85 3906.06 1032.16 3915.89 1042.56 3923.18C1054.35 3931.45 1068.3 3936.09 1082.69 3936.52C1097.08 3936.96 1111.29 3933.18 1123.56 3925.64C1134.38 3918.99 1143.27 3909.68 1149.42 3898.64C1150.79 3896.18 1153.8 3895.08 1156.35 3896.27L1199.63 3916.58Z" fill="#20304D" stroke="#000" stroke-width="3.71429" stroke-linejoin="round"/>
                    <path d="M967.178 3909.51C964.565 3910.55 961.596 3909.28 960.66 3906.62C952.921 3884.7 951.109 3861.09 955.455 3838.19C959.8 3815.29 970.134 3793.98 985.365 3776.42C987.207 3774.29 990.437 3774.2 992.488 3776.12L1027.37 3808.81C1029.42 3810.73 1029.51 3813.94 1027.73 3816.12C1019.99 3825.62 1014.72 3836.91 1012.42 3849C1010.13 3861.09 1010.9 3873.53 1014.63 3885.2C1015.48 3887.88 1014.22 3890.83 1011.61 3891.87L967.178 3909.51Z" fill="#CBC9C5" stroke="#000" stroke-width="3.71429" stroke-linejoin="round"/>
                    <path d="M989.601 3766.18C987.551 3764.26 987.442 3761.03 989.443 3759.06C1001.03 3747.63 1014.64 3738.43 1029.6 3731.95C1044.56 3725.46 1060.58 3721.82 1076.85 3721.18C1079.66 3721.07 1081.94 3723.36 1081.94 3726.17L1081.96 3773.98C1081.96 3776.79 1079.68 3779.05 1076.88 3779.25C1068.54 3779.83 1060.35 3781.82 1052.66 3785.15C1044.97 3788.49 1037.91 3793.1 1031.79 3798.79C1029.73 3800.7 1026.52 3800.82 1024.46 3798.89L989.601 3766.18Z" fill="#C3DDFF" stroke="#000" stroke-width="3.71429" stroke-linejoin="round"/>
                    <g font-family="Montserrat, sans-serif" font-size="12" font-weight="500" fill="#fff">
                        <rect x="1234" y="3805" width="26" height="22" rx="4" fill="#20304D"/>
                        <text x="1268" y="3820.4">63%</text>
                        <rect x="1234" y="3835" width="26" height="22" rx="4" fill="#9F8965"/>
                        <text x="1268" y="3850.4">63%</text>
                        <rect x="1234" y="3865" width="26" height="22" rx="4" fill="#CBC9C5"/>
                        <text x="1268" y="3880.4">99%</text>
                        <rect x="1234" y="3895" width="26" height="22" rx="4" fill="#C3DDFF"/>
                        <text x="1268" y="3910.4">18%</text>
                    </g>
                </svg>
            </div>
        </div>
    </section>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var items = document.querySelectorAll('.sd-faq__item');
        items.forEach(function (item) {
            var btn = item.querySelector('.sd-faq__q');
            btn.addEventListener('click', function () {
                var willOpen = !item.classList.contains('is-open');
                items.forEach(function (other) {
                    other.classList.remove('is-open');
                    other.querySelector('.sd-faq__q').setAttribute('aria-expanded', 'false');
                });
                if (willOpen) {
                    item.classList.add('is-open');
                    btn.setAttribute('aria-expanded', 'true');
                }
            });
        });
    });
</script>
@endsection

@section('footer')
    @include('layouts.includes.footer3')
@endsection
