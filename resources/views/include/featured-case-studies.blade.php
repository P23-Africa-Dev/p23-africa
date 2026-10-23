{{-- Featured case studies block, duplicated from welcome.blade.php ("Commercial Results in Real Markets"). --}}
@once
<style>
    /* ========================================================
       HOMEPAGE FEATURED CASE STUDIES (COMMERCIAL RESULTS)
       ======================================================== */
    .home-casestudy-section {
        position: relative;
        width: 100%;
        padding: clamp(65px, 7vw, 105px) 0;
        background: #f8faf9;
        border-top: 1px solid rgba(13, 64, 54, 0.06);
        border-bottom: 1px solid rgba(13, 64, 54, 0.06);
    }

    .home-casestudy-container {
        max-width: 1220px;
        margin: 0 auto;
        padding: 0 clamp(20px, 3.5vw, 36px);
    }

    .home-casestudy-header {
        text-align: center;
        max-width: 780px;
        margin: 0 auto clamp(32px, 4vw, 44px);
    }

    .home-casestudy-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-family: "GT Walsheim ProCB", "Montserrat", sans-serif;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #003c32;
        background: rgba(39, 230, 167, 0.16);
        border: 1px solid rgba(39, 230, 167, 0.38);
        padding: 6px 16px;
        border-radius: 100px;
        margin-bottom: 16px;
    }

    .home-casestudy-title {
        font-family: "GT Walsheim ProCB", "Montserrat", sans-serif;
        font-size: clamp(28px, 3.5vw, 42px);
        font-weight: 700;
        line-height: 1.22;
        color: #07483e;
        margin: 0 0 14px 0;
        letter-spacing: -0.02em;
    }

    .home-casestudy-subtitle {
        font-family: "GT Walsheim Trial Cn Rg", "Inter", sans-serif;
        font-size: clamp(16px, 1.25vw, 18.5px);
        line-height: 1.6;
        color: #4e635e;
        margin: 0;
    }

    /* Tabs Bar (matching market-entry capsule with homepage dark teal theme) */
    .home-tabs-wrapper {
        width: 100%;
        display: flex;
        justify-content: center;
        margin: 0 auto clamp(28px, 3.5vw, 40px);
    }

    .home-tabs-container {
        width: 100%;
        max-width: 520px;
        height: 56px;
        border-radius: 100px;
        background-color: #07483e;
        padding: 5px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 6px;
        box-shadow: 0 10px 28px rgba(7, 72, 62, 0.18);
        position: relative;
    }

    .home-tab-btn {
        flex: 1;
        height: 46px;
        border-radius: 100px;
        border: none;
        background: transparent;
        color: #d0eee9;
        font-family: "GT Walsheim ProCB", "Montserrat", sans-serif;
        font-weight: 600;
        font-size: clamp(13px, 1.1vw, 14.5px);
        letter-spacing: 0.02em;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 8px 16px;
        white-space: nowrap;
        user-select: none;
    }

    .home-tab-btn:hover:not(.active) {
        color: #ffffff;
        background-color: rgba(255, 255, 255, 0.08);
    }

    .home-tab-btn.active {
        background-color: #ffffff;
        color: #07483e;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
    }

    .home-tab-btn .tab-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background-color: #27E6A7;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .home-tab-btn.active .tab-dot {
        opacity: 1;
        background-color: #07483e;
    }

    /* Panes and Cards */
    .home-case-panes-wrapper {
        position: relative;
        min-height: 420px;
    }

    .home-case-pane {
        display: none;
        opacity: 0;
        transform: translateY(12px);
        transition: opacity 0.35s ease, transform 0.35s ease;
    }

    .home-case-pane.active {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }

    .home-case-card {
        background: #ffffff;
        border: 1px solid rgba(13, 64, 54, 0.1);
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 16px 44px rgba(13, 64, 54, 0.07);
        display: grid;
        grid-template-columns: 46% 54%;
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.35s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.35s ease;
    }

    .home-case-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 24px 54px rgba(13, 64, 54, 0.12);
        border-color: rgba(39, 230, 167, 0.45);
    }

    .home-case-visual {
        position: relative;
        overflow: hidden;
        min-height: 380px;
        background: #02241e;
    }

    .home-case-visual img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .home-case-card:hover .home-case-visual img {
        transform: scale(1.05);
    }

    .home-case-badge {
        position: absolute;
        top: 20px;
        left: 20px;
        background: rgba(7, 72, 62, 0.9);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        color: #ffffff;
        font-family: "GT Walsheim ProCB", "Montserrat", sans-serif;
        font-size: 12.5px;
        font-weight: 600;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        padding: 6px 14px;
        border-radius: 100px;
        border: 1px solid rgba(39, 230, 167, 0.4);
        z-index: 2;
    }

    .home-case-article {
        padding: clamp(28px, 4vw, 44px);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .home-case-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 16px;
        font-family: "GT Walsheim ProCB", "Montserrat", sans-serif;
        font-size: 13.5px;
        color: #6a7f7a;
    }

    .home-case-meta span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .home-case-meta i {
        color: #07483e;
        font-size: 14px;
    }

    .home-case-heading {
        font-family: "GT Walsheim ProCB", "Montserrat", sans-serif;
        font-size: clamp(22px, 2.3vw, 29px);
        font-weight: 700;
        line-height: 1.3;
        color: #07483e;
        margin: 0 0 16px 0;
        letter-spacing: -0.01em;
    }

    .home-case-description {
        font-family: "GT Walsheim Trial Cn Rg", "Inter", sans-serif;
        font-size: clamp(16px, 1.2vw, 17.5px);
        line-height: 1.6;
        color: #405550;
        margin: 0 0 24px 0;
    }

    .home-case-metrics {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        padding: 16px 18px;
        background: #f4faf8;
        border: 1px solid rgba(13, 64, 54, 0.08);
        border-radius: 14px;
        margin-bottom: 26px;
    }

    .home-metric-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .home-metric-value {
        font-family: "GT Walsheim ProCB", "Montserrat", sans-serif;
        font-size: clamp(18px, 1.7vw, 22px);
        font-weight: 700;
        color: #07483e;
        line-height: 1.1;
    }

    .home-metric-label {
        font-family: "GT Walsheim Trial Cn Rg", "Inter", sans-serif;
        font-size: 12.5px;
        color: #637772;
        line-height: 1.3;
    }

    .home-case-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 8px;
    }

    .home-case-link {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-family: "GT Walsheim ProCB", "Montserrat", sans-serif;
        font-size: 15.5px;
        font-weight: 700;
        color: #07483e;
        text-decoration: none;
        padding: 12px 24px;
        background: rgba(13, 64, 54, 0.06);
        border-radius: 100px;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .home-case-link:hover {
        background: #07483e;
        color: #ffffff !important;
        transform: translateX(4px);
        box-shadow: 0 8px 20px rgba(7, 72, 62, 0.2);
    }

    .home-case-link .arrow {
        transition: transform 0.3s ease;
    }

    .home-case-link:hover .arrow {
        transform: translateX(4px);
    }

    .home-casestudy-all-link {
        text-align: center;
        margin-top: clamp(28px, 3.5vw, 40px);
    }

    .home-casestudy-all-link a {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-family: "GT Walsheim ProCB", "Montserrat", sans-serif;
        font-size: 15px;
        font-weight: 600;
        color: #07483e;
        text-decoration: none;
        border-bottom: 2px solid rgba(39, 230, 167, 0.7);
        padding-bottom: 3px;
        transition: all 0.25s ease;
    }

    .home-casestudy-all-link a:hover {
        color: #00221c;
        border-bottom-color: #07483e;
        transform: translateX(2px);
    }

    @media (max-width: 991px) {
        .home-case-card {
            grid-template-columns: 1fr;
        }
        .home-case-visual {
            min-height: 260px;
            max-height: 320px;
        }
    }

    @media (max-width: 600px) {
        .home-tabs-container {
            height: auto;
            border-radius: 18px;
            flex-direction: column;
            padding: 6px;
        }
        .home-tab-btn {
            width: 100%;
            height: 42px;
            border-radius: 12px;
        }
        .home-case-metrics {
            grid-template-columns: 1fr;
            gap: 10px;
        }
    }
</style>
@endonce

    {{-- ==========================================
         FEATURED CASE STUDIES (COMMERCIAL RESULTS)
         ========================================== --}}
    <section class="home-casestudy-section" id="featured-case-studies" aria-label="Featured Case Studies">
        <div class="home-casestudy-container">
            
            {{-- Section Header --}}
            <div class="home-casestudy-header">
                <span class="home-casestudy-kicker">CASE STUDIES</span>
                <h2 class="home-casestudy-title">Commercial Results in Real Markets</h2>
                <p class="home-casestudy-subtitle">
                    Examine how international manufacturers and platforms validate real buyer demand and execute successful market expansion across Africa.
                </p>
            </div>

            {{-- Tabs Bar (Inspired by market-entry lines 282-312, aligned with homepage design system) --}}
            <div class="home-tabs-wrapper">
                <div class="home-tabs-container" role="tablist" aria-label="Case Study Industry Tabs">
                    <button type="button" 
                            class="home-tab-btn active" 
                            role="tab" 
                            aria-selected="true" 
                            aria-controls="home-pane-industrial" 
                            data-target="#home-pane-industrial"
                            id="tab-industrial"
                            onclick="switchHomeCaseTab('home-pane-industrial', this)">
                        <span class="tab-dot"></span>
                        Industrial Equipment
                    </button>
                    <button type="button" 
                            class="home-tab-btn" 
                            role="tab" 
                            aria-selected="false" 
                            aria-controls="home-pane-construction" 
                            data-target="#home-pane-construction"
                            id="tab-construction"
                            onclick="switchHomeCaseTab('home-pane-construction', this)">
                        <span class="tab-dot"></span>
                        Construction Equipment
                    </button>
                </div>
            </div>

            {{-- Case Study Showcase Panes --}}
            <div class="home-case-panes-wrapper">
                
                {{-- Pane 1: Industrial Equipment Manufacturer --}}
                <div class="home-case-pane active" id="home-pane-industrial" role="tabpanel" aria-labelledby="tab-industrial">
                    <div class="home-case-card">
                        <div class="home-case-visual">
                            <img src="{{ asset('images/case-studies/thermoflow.png') }}" 
                                 alt="Testing Demand for an Industrial Equipment Manufacturer" 
                                 loading="lazy">
                            <span class="home-case-badge">Industrial Equipment</span>
                        </div>

                        <div class="home-case-article">
                            <div>
                                <div class="home-case-meta">
                                    <span><i class="bi bi-geo-alt"></i> Nigeria (Lagos & Abuja)</span>
                                    <span><i class="bi bi-clock"></i> 10-Week Demand Testing</span>
                                </div>

                                <h3 class="home-case-heading">
                                    Testing Demand for an Industrial Equipment Manufacturer
                                </h3>

                                <p class="home-case-description">
                                    Validated buyer interest, gathered market pricing insights and identified potential distribution partners in Nigeria.
                                </p>

                                <div class="home-case-metrics">
                                    <div class="home-metric-item">
                                        <span class="home-metric-value">312</span>
                                        <span class="home-metric-label">Target Accounts</span>
                                    </div>
                                    <div class="home-metric-item">
                                        <span class="home-metric-value">47</span>
                                        <span class="home-metric-label">Qualified Opportunities</span>
                                    </div>
                                    <div class="home-metric-item">
                                        <span class="home-metric-value">Verified</span>
                                        <span class="home-metric-label">Distribution Route</span>
                                    </div>
                                </div>
                            </div>

                            <div class="home-case-footer">
                                <a href="{{ url('case-studies/thermoflow-hvac') }}" class="home-case-link" id="btn-read-case-industrial">
                                    Read the case study <span class="arrow">→</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pane 2: Construction Equipment Platform --}}
                <div class="home-case-pane" id="home-pane-construction" role="tabpanel" aria-labelledby="tab-construction">
                    <div class="home-case-card">
                        <div class="home-case-visual">
                            <img src="{{ asset('images/case-studies/construction-platform.png') }}" 
                                 alt="Supporting a Construction Equipment Platform's Market Expansion" 
                                 loading="lazy">
                            <span class="home-case-badge">Equipment Platform</span>
                        </div>

                        <div class="home-case-article">
                            <div>
                                <div class="home-case-meta">
                                    <span><i class="bi bi-geo-alt"></i> East & West Africa</span>
                                    <span><i class="bi bi-clock"></i> Multi-Market Expansion</span>
                                </div>

                                <h3 class="home-case-heading">
                                    Supporting a Construction Equipment Platform's Market Expansion
                                </h3>

                                <p class="home-case-description">
                                    Tested demand across multiple African markets, developed local partnerships and supported commercial launch execution.
                                </p>

                                <div class="home-case-metrics">
                                    <div class="home-metric-item">
                                        <span class="home-metric-value">3+</span>
                                        <span class="home-metric-label">African Markets</span>
                                    </div>
                                    <div class="home-metric-item">
                                        <span class="home-metric-value">Direct</span>
                                        <span class="home-metric-label">Partner Validation</span>
                                    </div>
                                    <div class="home-metric-item">
                                        <span class="home-metric-value">De-risked</span>
                                        <span class="home-metric-label">Market Execution</span>
                                    </div>
                                </div>
                            </div>

                            <div class="home-case-footer">
                                <a href="{{ url('case-studies/liftmasters-international') }}" class="home-case-link" id="btn-read-case-construction">
                                    Read the case study <span class="arrow">→</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- All Case Studies Link --}}
            <div class="home-casestudy-all-link">
                <a href="{{ url('case-studies') }}" id="link-explore-all-case-studies">
                    Explore all verified market case studies <span>→</span>
                </a>
            </div>

        </div>
    </section>

@once
<script>
        function switchHomeCaseTab(paneId, btn) {
            const buttons = document.querySelectorAll('.home-tab-btn');
            const panes = document.querySelectorAll('.home-case-pane');

            buttons.forEach(b => {
                b.classList.remove('active');
                b.setAttribute('aria-selected', 'false');
            });

            if (btn) {
                btn.classList.add('active');
                btn.setAttribute('aria-selected', 'true');
            }

            panes.forEach(pane => {
                pane.classList.remove('active');
            });

            const target = document.getElementById(paneId);
            if (target) {
                target.classList.add('active');
            }
        }

        // Attach event listeners
        document.addEventListener('DOMContentLoaded', function () {
            const homeTabButtons = document.querySelectorAll('.home-tab-btn');
            homeTabButtons.forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('data-target') ? this.getAttribute('data-target').replace('#', '') : '';
                    if (targetId) {
                        switchHomeCaseTab(targetId, this);
                    }
                });
            });
        });
</script>
@endonce
