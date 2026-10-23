<!-- ==========================================
     FOOTER 3 - P23 AFRICA
     Figma Pixel-Perfect Implementation
     ========================================== -->
<footer class="footer3" role="contentinfo">
    <div class="footer3-container">
        <div class="footer3-grid">
            
            {{-- Column 1: Brand Logo & UK Registration --}}
            <div class="footer3-col footer3-col-brand">
                <a href="{{ route('homepage') }}" class="footer3-logo-link" aria-label="P23 Africa Homepage">
                    <img src="{{ asset('images/p23-logo-white.png') }}" 
                         alt="P23 Africa" 
                         class="footer3-logo" 
                         loading="lazy">
                </a>
                <p class="footer3-reg-text">
                    P23 Africa LTD is registered in the United Kingdom. Company No: 15246700
                </p>
            </div>

            {{-- Column 2: Who We Are --}}
            <div class="footer3-col">
                <h3 class="footer3-heading">Who We Are</h3>
                <ul class="footer3-nav-list">
                    <li><a href="{{ url('about') }}" class="footer3-nav-link">About Us</a></li>
                    <li><a href="{{ route('brn') }}" class="footer3-nav-link">Business Network</a></li>
                </ul>
            </div>

            {{-- Column 3: What We Do --}}
            <div class="footer3-col">
                <h3 class="footer3-heading">What We Do</h3>
                <ul class="footer3-nav-list">
                    <li><a href="{{ url('market-entry') }}" class="footer3-nav-link">Market Entry &amp; Expansion</a></li>
                    <li><a href="{{ url('services') }}#growth-plan" class="footer3-nav-link">Verify Distributors</a></li>
                    <li><a href="{{ url('services') }}#growth-plan" class="footer3-nav-link">Sales Demand Testing</a></li>
                    <li><a href="{{ url('services') }}#growth-plan" class="footer3-nav-link">Sales/Market Support</a></li>
                    <li><a href="{{ url('services') }}#growth-plan" class="footer3-nav-link">Investment Readiness</a></li>
                </ul>
            </div>

            {{-- Column 4: Contact Us & Social Links --}}
            <div class="footer3-col">
                <h3 class="footer3-heading">Contact Us</h3>
                <div class="footer3-social-icons">
                    
                    {{-- LinkedIn --}}
                    <a href="https://linkedin.com/company/p23-africa/" target="_blank" rel="noopener noreferrer" class="footer3-social-link" aria-label="LinkedIn">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.7778 0C18.3671 0 18.9324 0.234126 19.3491 0.650874C19.7659 1.06762 20 1.63285 20 2.22222V17.7778C20 18.3671 19.7659 18.9324 19.3491 19.3491C18.9324 19.7659 18.3671 20 17.7778 20H2.22222C1.63285 20 1.06762 19.7659 0.650874 19.3491C0.234126 18.9324 0 18.3671 0 17.7778V2.22222C0 1.63285 0.234126 1.06762 0.650874 0.650874C1.06762 0.234126 1.63285 0 2.22222 0H17.7778ZM17.2222 17.2222V11.3333C17.2222 10.3727 16.8406 9.45133 16.1613 8.77204C15.482 8.09274 14.5607 7.71111 13.6 7.71111C12.6556 7.71111 11.5556 8.28889 11.0222 9.15556V7.92222H7.92222V17.2222H11.0222V11.7444C11.0222 10.8889 11.7111 10.1889 12.5667 10.1889C12.9792 10.1889 13.3749 10.3528 13.6666 10.6445C13.9583 10.9362 14.1222 11.3319 14.1222 11.7444V17.2222H17.2222ZM4.31111 6.17778C4.80618 6.17778 5.28098 5.98111 5.63104 5.63104C5.98111 5.28098 6.17778 4.80618 6.17778 4.31111C6.17778 3.27778 5.34444 2.43333 4.31111 2.43333C3.81309 2.43333 3.33547 2.63117 2.98332 2.98332C2.63117 3.33547 2.43333 3.81309 2.43333 4.31111C2.43333 5.34444 3.27778 6.17778 4.31111 6.17778ZM5.85556 17.2222V7.92222H2.77778V17.2222H5.85556Z" fill="#ECECEB"/>
                        </svg>
                    </a>

                    {{-- Facebook --}}
                    <a href="https://facebook.com/p23africa" target="_blank" rel="noopener noreferrer" class="footer3-social-link" aria-label="Facebook">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.7778 0H2.22222C1 0 0 1 0 2.22222V17.7778C0 19.0011 1 20 2.22222 20H10V12.2222H7.77778V9.47222H10V7.19444C10 4.79 11.3467 3.10111 14.1844 3.10111L16.1878 3.10333V5.99778H14.8578C13.7533 5.99778 13.3333 6.82667 13.3333 7.59556V9.47333H16.1867L15.5556 12.2222H13.3333V20H17.7778C19 20 20 19.0011 20 17.7778V2.22222C20 1 19 0 17.7778 0Z" fill="#ECECEB"/>
                        </svg>
                    </a>

                    {{-- Instagram --}}
                    <a href="https://www.instagram.com/p23africa/" target="_blank" rel="noopener noreferrer" class="footer3-social-link" aria-label="Instagram">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.99731 6.66512C8.16111 6.66512 6.66262 8.16361 6.66262 9.99981C6.66262 11.836 8.16111 13.3345 9.99731 13.3345C11.8335 13.3345 13.332 11.836 13.332 9.99981C13.332 8.16361 11.8335 6.66512 9.99731 6.66512ZM19.9989 9.99981C19.9989 8.61891 20.0114 7.25051 19.9338 5.8721C19.8563 4.27105 19.4911 2.85012 18.3203 1.67935C17.147 0.506076 15.7286 0.143338 14.1275 0.0657868C12.7466 -0.0117642 11.3782 0.000744099 9.99981 0.000744099C8.61891 0.000744099 7.25051 -0.0117642 5.8721 0.0657868C4.27105 0.143338 2.85012 0.508578 1.67935 1.67935C0.506076 2.85262 0.143338 4.27105 0.0657868 5.8721C-0.0117642 7.25301 0.000744099 8.62141 0.000744099 9.99981C0.000744099 11.3782 -0.0117642 12.7491 0.0657868 14.1275C0.143338 15.7286 0.508578 17.1495 1.67935 18.3203C2.85262 19.4936 4.27105 19.8563 5.8721 19.9338C7.25301 20.0114 8.62141 19.9989 9.99981 19.9989C11.3807 19.9989 12.7491 20.0114 14.1275 19.9338C15.7286 19.8563 17.1495 19.4911 18.3203 18.3203C19.4936 17.147 19.8563 15.7286 19.9338 14.1275C20.0139 12.7491 19.9989 11.3807 19.9989 9.99981ZM9.99731 15.1307C7.15795 15.1307 4.86644 12.8392 4.86644 9.99981C4.86644 7.16045 7.15795 4.86894 9.99731 4.86894C12.8367 4.86894 15.1282 7.16045 15.1282 9.99981C15.1282 12.8392 12.8367 15.1307 9.99731 15.1307ZM15.3383 5.85709C14.6754 5.85709 14.14 5.32174 14.14 4.6588C14.14 3.99587 14.6754 3.46052 15.3383 3.46052C16.0013 3.46052 16.5366 3.99587 16.5366 4.6588C16.5368 4.81622 16.5059 4.97213 16.4458 5.1176C16.3856 5.26308 16.2974 5.39525 16.1861 5.50656C16.0748 5.61787 15.9426 5.70613 15.7971 5.76628C15.6516 5.82643 15.4957 5.85729 15.3383 5.85709Z" fill="#ECECEB"/>
                        </svg>
                    </a>

                    {{-- YouTube --}}
                    <a href="https://www.youtube.com/channel/UCmhdbCCyjpINgZ0Fgu20ZBQ" target="_blank" rel="noopener noreferrer" class="footer3-social-link" aria-label="YouTube">
                        <svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 0C10.855 0 11.732 0.0220001 12.582 0.0580001L13.586 0.106L14.547 0.163L15.447 0.224L16.269 0.288C17.1612 0.355891 18.0008 0.736434 18.64 1.3626C19.2791 1.98877 19.6768 2.8204 19.763 3.711L19.803 4.136L19.878 5.046C19.948 5.989 20 7.017 20 8C20 8.983 19.948 10.011 19.878 10.954L19.803 11.864L19.763 12.289C19.6768 13.1798 19.2789 14.0115 18.6396 14.6377C18.0002 15.2639 17.1604 15.6443 16.268 15.712L15.448 15.775L14.548 15.837L13.586 15.894L12.582 15.942C11.7218 15.9793 10.861 15.9986 10 16C9.13902 15.9986 8.27817 15.9793 7.418 15.942L6.414 15.894L5.453 15.837L4.553 15.775L3.731 15.712C2.83881 15.6441 1.9992 15.2636 1.36004 14.6374C0.720888 14.0112 0.323191 13.1796 0.237 12.289L0.197 11.864L0.122 10.954C0.0458327 9.97112 0.00513964 8.98581 0 8C0 7.017 0.052 5.989 0.122 5.046L0.197 4.136L0.237 3.711C0.323157 2.82055 0.720712 1.98905 1.35966 1.36291C1.99861 0.736759 2.83799 0.356116 3.73 0.288L4.551 0.224L5.451 0.163L6.413 0.106L7.417 0.0580001C8.2775 0.0207272 9.13869 0.00138958 10 0ZM8 5.575V10.425C8 10.887 8.5 11.175 8.9 10.945L13.1 8.52C13.1914 8.46739 13.2673 8.39162 13.3201 8.30033C13.3729 8.20904 13.4007 8.10545 13.4007 8C13.4007 7.89455 13.3729 7.79096 13.3201 7.69967C13.2673 7.60838 13.1914 7.53261 13.1 7.48L8.9 5.056C8.80876 5.00332 8.70526 4.9756 8.5999 4.97562C8.49455 4.97563 8.39106 5.00339 8.29983 5.0561C8.20861 5.1088 8.13287 5.1846 8.08024 5.27587C8.02761 5.36713 7.99993 5.47065 8 5.576V5.575Z" fill-rule="evenodd" clip-rule="evenodd" fill="#ECECEB"/>
                        </svg>
                    </a>

                </div>
            </div>

            {{-- Column 5: Subscribe to get Updates --}}
            <div class="footer3-col footer3-col-subscribe">
                <h3 class="footer3-heading">Subscribe to get Updates</h3>
                <form class="footer3-form" action="#" method="POST" onsubmit="event.preventDefault();">
                    @csrf
                    <input type="email" 
                           class="footer3-input" 
                           placeholder="Enter your email" 
                           required 
                           aria-label="Enter your email to subscribe">
                    <button type="submit" class="footer3-submit-btn">
                        Subscribe <span class="btn-arrow">→</span>
                    </button>
                </form>
            </div>

        </div>

        {{-- Bottom Legal / Copyright Row --}}
        <div class="footer3-bottom">
            <p class="footer3-legal-text">
                &copy;P23 Africa 2025 &bull; 
                <a href="{{ url('privacy-policy') }}">Privacy Policy</a> &bull; 
                <a href="{{ url('terms-and-conditions') }}">Terms &amp; Conditions</a>
            </p>
        </div>
    </div>
</footer>
