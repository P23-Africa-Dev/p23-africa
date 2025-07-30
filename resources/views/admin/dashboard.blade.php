<x-app-layout>

    <x-slot name="header">
        <div class="d-flex justify-content-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            @if (auth()->user() && auth()->user()->hasRole('admin'))
                <a href="{{ route('admin.staff.index') }}" class="btn btn-dark">Staffs</a>
            @endif
        </div>

    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <fieldset class="border border-dark rounded-3 p-3">
                        <legend class="float-none w-auto px-3">
                            <h2>Admin Dashboard</h2>
                        </legend>

                        {{-- @can('manage_events') --}}
                        <div class="my-3">
                            <a href="{{ route('admin.events.index') }}" class="link-dark">Manage Events <i
                                    class="fa fa-arrow-right"></i></a>
                        </div>
                        <hr>
                        {{-- @endcan --}}

                        {{-- @can('resource_hub') --}}
                        <div class="my-3">
                            <a href="{{ route('admin.blogs.index') }}" class="link-dark">Resource Hub <i
                                    class="fa fa-arrow-right"></i></a>
                        </div>
                        <hr>
                        {{-- @endcan --}}


                        {{-- @can('view_students') --}}
                        <div class="my-3">
                            <a href="{{ route('admin.students.index') }}" class="link-dark">View Students <i
                                    class="fa fa-arrow-right"></i></a>
                        </div>
                        <hr>
                        {{-- @endcan --}}

                        @if (auth()->user() && auth()->user()->hasRole('admin'))
                            <br><br><br>
                            <div class="my-3">
                                <a href="{{ route('admin.brn.index') }}" class="link-dark">BRN Interest Applications <i
                                        class="fa fa-arrow-right"></i></a>
                            </div>
                            <hr>
                            <div class="my-3">
                                <a href="{{ route('admin.clicks.report') }}" class="link-dark">Pages Report (Clicks) <i
                                        class="fa fa-arrow-right"></i></a>
                            </div>
                            <hr>
                            <div class="my-3">
                                <a href="{{ route('admin.staff.index') }}" class="link-dark">Manage Staff <i
                                        class="fa fa-arrow-right"></i></a>
                            </div>
                        @endif
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
