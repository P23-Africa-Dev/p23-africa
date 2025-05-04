@extends('layouts.master')
@section('title', 'Services | P23 Africa')


@section('content')
    <section class="services-hero about animate-on-scroll">
        <div class="hero-overlay"></div>
        <div class="container hero-content">
            <div class="hero" >
                    <div class="left-content">
                        <h2>Our Services</h2>
                         <a href="{{ url('contact') }}" class="read-more">Contact Us <span>→</span></a>
                    </div>
                  <div class="right-content">
                     <div class="content bg-color1">
                        <div class="icon-box" >
                            <img src="{{ asset('images/strategy.png') }}" alt="">
                            <h4>Business Strategy</h4>
                        </div>
                     </div>
                     <div class="content bg-color2">
                        <div class="icon-box" >
                            <img src="{{ asset('images/sale-tag-02.png') }}" alt="">
                            <h4>Marketing & Sales</h4>
                        </div>
                     </div>
                     <div class="content bg-color3">
                        <div class="icon-box" >
                            <img src="{{ asset('images/activity-03.png') }}" alt="">
                            <h4>Operation & Efficiency</h4>
                        </div>
                     </div>
                     <div class="content bg-color4">
                        <div class="icon-box" >
                            <img src="{{ asset('images/arrow-expand.png') }}" alt="">
                            <h4>Expansion</h4>
                        </div>
                     </div>
                  </div>
            </div>
        </div>
    </section>


    <section class="services animate-on-scroll">
        <div class="">
             <div class="service-top-content">
                <div class="container position-relative">
                    <div class="top-image">
                        <img src="{{ asset('images/service-group.png') }}" alt="">
                    </div>
                    <div class="row g-5 g-sm-4 g-md-3 g-lg-4 g-xl-0">
                        <div class="  col-12  col-lg-4" id="strategy">
                            <div class="services-content bg-color1 roundedtop">
                                <div class="icon-box d-flex flex-column justify-content-start align-items-start text-left" >
                                    <img src="{{ asset('images/strategy.png') }}" alt="">
                                    <h4 class="text-left">Business Strategy</h4>
        
                                    <p>We help you build a clear plan for growth and success. Our team works closely with you to understand your goals, challenges, and the market you’re in. We create tailored strategies that show you the best steps to take, whether you’re starting a new business, growing an existing one, or expanding into new markets. With expert advice, market insights, and practical solutions, we give you the tools to make confident decisions and achieve lasting success.</p>
                                </div>
                             </div>
                        </div>
                    
                        <div class=" col-12 col-lg-4" id="market">
                            <div class="services-content bg-color2 ">
                                <div class="icon-box d-flex flex-column justify-content-start align-items-start text-left" >
                                    <img src="{{ asset('images/target-03.png') }}" alt="">
                                    <h4 class="text-left">Market Entry</h4>
        
                                    <p>We help you successfully enter and grow in African markets. Our team provides expert guidance on local regulations, business culture, and market opportunities across the continent. We develop tailored entry strategies, connect you with trusted local partners, and support you through every step—from market research to launch. Whether you’re expanding into one country or across multiple regions, we make your move into Africa smooth, strategic, and successful.</p>
                                </div>
                             </div>
                        </div>
                    
                        <div class=" col-12 col-lg-4" id="research">
                            <div class="services-content bg-color3 roundedbottom ">
                                <div class="icon-box d-flex flex-column justify-content-start align-items-start text-left" >
                                    <img src="{{ asset('images/share-knowledge.png') }}" alt="">
                                    <h4 class="text-left">Business Research</h4>
        
                                    <p>We help you make smarter business decisions with reliable research and insights. Our team gathers and analyzes information about markets, industries, customers, and competitors, giving you a clear understanding of the opportunities and challenges ahead. Whether you’re entering a new market, launching a product, or planning for growth, we provide the data and analysis you need to build strong, informed strategies.</p>
                                </div>
                             </div>
                        </div>
                    </div>
                </div>
             </div>
             <div class="service-middle-content"></div>
             <div class="service-bottom-content">
                 <div class="container">
                    <div class="row g-5 g-sm-4 g-md-3 g-lg-4 g-xl-0">
                        <div class=" col-md-4" id="sales">
                            <div class="services-content bg-color4 ">
                                <div class="icon-box d-flex flex-column justify-content-start align-items-start text-left" >
                                    <img src="{{ asset('images/sale-tag-4.png') }}" alt="">
                                    <h4 class="text-left">Sales</h4>
        
                                    <p>We help you grow your sales, reach more customers, and build stronger sales teams. Our experts work closely with you to understand your products, services, and target market. We create clear sales strategies, improve your processes, and provide hands-on training to boost your team’s skills and confidence. Whether you need help closing more deals, entering new markets, or increasing revenue, we give you the tools, support, and training to grow your business successfully.</p>
                                </div>
                             </div>
                        </div>
                    
                        <div class="  col-md-4" id="marketing">
                            <div class="services-content bg-color5 ">
                                <div class="icon-box d-flex flex-column justify-content-start align-items-start text-left" >
                                    <img src="{{ asset('images/marketing.png') }}" alt="">
                                    <h4 class="text-left">Marketing</h4>
        
                                    <p>We help you connect with the right customers and grow your brand. Our team works with you to understand your business, your audience, and your goals. We create custom marketing strategies that include branding, social media, advertising, and more. Whether you’re launching a new product, reaching new markets, or building your online presence, we give you the tools and support to promote your business effectively and drive real results.</p>
                                </div>
                             </div>
                        </div>
                    
                        <div class=" col-md-4" id="audit">
                            <div class="services-content bg-color6 ">
                                <div class="icon-box d-flex flex-column justify-content-start align-items-start text-left" >
                                    <img src="{{ asset('images/teaching6.png') }}" alt="">
                                    <h4 class="text-left">Business Audit</h4>
        
                                    <p>We help you take a closer look at your business to find strengths, weaknesses, & new opportunities. Our team reviews your operations, finances, strategy, & performance to give you a full picture of where you stand. We provide clear reports & practical recommendations to help you improve efficiency, reduce risks, & drive growth. Whether you’re preparing for expansion or just want a health check, our audits give you the insights you need to move forward with confidence.</p>
                                </div>
                             </div>
                        </div> 
                    </div>
                 </div>
             </div>
        </div>
    </section>


    <section class="research animate-on-scroll">
        <div class="container">
            <div class="row g-10">
                <!-- Left Column -->
                <div class="col-12 col-md-6">
                    <div class="resarch-image">
                        <img src="{{ asset('images/research-image.png') }}" alt="People"
                        class="img-fluid">
                    </div>
                </div>
    
                <!-- Right Column -->
                <div class="col-12 col-md-6 research-content d-flex flex-column justify-content-start " >
                    <h2 class="">Global Reach. <span>Local Approach</span> </h2>
                    <p class="">
                        We partner with entrepreneurs and businesses to understand their unique  needs, align them with their goals, and set a clear path to execution and results. 
                    </p>
                    <p class="">
                        Our consulting services come with personalised and hands-on support, and our team is committed to excellence, intergrity and collaboration.Let's help you thrive in the dynamic and unpredictable African market.
                    </p>
                    <div>
                        <a href="{{ url('contact') }}" class="read-more">Contact Us <span>→</span></a>
                    </div>
                </div>
            </div>
        </div>


        <div class="global_reach">
            <div class="row justify-content-center text-center stats me-auto ms-auto mt-5">
                <div class="col-6 col-md-3 stat-box">
                    <div class="number" id="countries"></div>
                    <div class="label">Countries</div>
                </div>
                <div class="col-6 col-md-3 stat-box">
                    <div class="number" id="businesses"></div>
                    <div class="label">Businesses</div>
                </div>
                <div class="col-6 col-md-3 stat-box">
                    <div class="number" id="partnership"></div>
                    <div class="label">Partnership</div>
                </div>
                <div class="col-6 col-md-3 stat-box">
                    <div class="number" id="network"></div>
                    <div class="label">Expert Network</div>
                </div>
            </div>
        </div>
    </section>



        
    <section class="px-5 animate-on-scroll">
        <div class="stats-section4">
            <div class="container">
                <div id="stats_img_desktop">
                    <img src="{{ asset('images/service-desktop.png') }}" alt="Card image">
                </div>
                <div id="stats_img_mobile">
                    <img src="{{ asset('images/service-mobile.png') }}" alt="Card image">
                </div>
            </div>
        </div>
    </section>
@endsection
