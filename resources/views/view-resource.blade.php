@extends('layouts.res-layout')
@section('title', 'Resource Details | P23 Africa')

@section('content')
    <section class="whole-view-resource">
        <section class="container py-5">
            <!-- Heading Section -->
            <section class="resourse-details-header">
                <div class="header">
                    <span>INTRO</span>
                    <h2 class="section-title">Regional Analysis</h2>
                    <p class="section-subtitle">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur ac orci quis elit malesuada
                        suscipit. Mauris efficitur, turpis ac ullamcorper posuere.
                    </p>
                </div>
            </section>

            <div class="dotted-divider"></div>

            <!-- Article Section -->
            <section class="resourse-details-content">
                <div class="row align-items-center">
                    <div class="col-12 my-5 mt-md-0">
                        <div class="article-title">Finibus Bonorum et Malorum</div>
                        <div class="date-author">Written by Jane Doe &nbsp; | &nbsp; Monday, May 20</div>
                        <div class="content">
                            <p>
                                Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quisquam itaque dolor earum quos saepe voluptatibus sapiente officiis debitis obcaecati cumque quod fugiat dolorem, nobis et nesciunt iure mollitia porro accusantium.
                                Rem illo qui quia nesciunt porro enim fugit velit, error itaque totam aliquam tempore cumque deleniti temporibus rerum officiis repellendus amet maiores perspiciatis beatae dolorum eos adipisci. Cumque, ipsum aperiam.
                                Pariatur ut nam impedit beatae nesciunt. Aspernatur, pariatur dicta, reiciendis consequatur maxime rerum aperiam perferendis quibusdam, repellendus deleniti sequi rem molestias error voluptates accusamus quam tempore aut voluptate sapiente temporibus!
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <img src="{{ asset('images/resource-detail.png') }}" alt="Insight Image" class="article-img" />
                    </div>
                    <div class="col-md-6 mt-4 mt-md-0">
                        <div class="content">
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc nec eros et eros pretium
                                fermentum. Sed quis suscipit sapien. Quisque malesuada, metus nec posuere congue, leo sem
                                consequat ante, eget pulvinar turpis felis in mauris.
                                <br>
                                “Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero.
                                Sed cursus ante dapibus diam.”
                                <br>
                                Aenean consequat, arcu ac finibus efficitur, mauris augue scelerisque nisi, eget blandit
                                felis arcu ut tellus. Vestibulum a diam vitae est ullamcorper dapibus nec sed justo. Aliquam
                                erat volutpat. Duis id congue eros.
                                <br>
                                Nam porttitor, elit a suscipit efficitur, libero nulla finibus eros, a tincidunt enim lacus
                                vel magna. Curabitur fringilla, sapien id feugiat feugiat, odio sapien pretium tellus, et
                                imperdiet sapien nisi sit amet nulla.
                            </p>
                            <a href="#" class="cta-button">Get More Insights →</a>
                        </div>
                    </div>
                </div>
            </section>
        </section>
    </section>
@endsection
