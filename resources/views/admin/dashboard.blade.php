<x-app-layout>

    <x-slot name="header">
        <div class="d-flex justify-content-between">
            <h2 class="font-semibold text-xl text-dark dark:text-gray-200 leading-tight dark-transition">
                {{ __('Dashboard') }}
            </h2>
            @if (auth()->user() && auth()->user()->hasRole('admin'))
                <a href="{{ route('admin.staff.index') }}"
                    class="btn btn-dark dark:bg-gray-600 dark:border-gray-600 dark:hover:bg-gray-700 dark-transition">Staffs</a>
            @endif
        </div>

    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg dark-transition">
                <div class="p-6 text-dark dark:text-gray-100 dark-transition">
                    <fieldset class="border border-dark dark:border-gray-500 rounded-3 p-3 dark-transition">
                        <legend class="float-none w-auto px-3 text-dark dark:text-gray-100">
                            <h2 class="text-dark dark:text-gray-100 dark-transition mb-0">Admin Dashboard</h2>
                        </legend>

                        {{-- @can('manage_events') --}}
                        <div class="my-3">
                            <a href="{{ route('admin.events.index') }}"
                                class="link-dark dark:text-blue-400 hover:text-blue-600 dark:hover:text-blue-300 dark-transition">
                                Manage Events <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                        <hr class="border-gray-300 dark:border-gray-600">
                        {{-- @endcan --}}

                        {{-- @can('resource_hub') --}}
                        <div class="my-3">
                            <a href="{{ route('admin.blogs.index') }}"
                                class="link-dark dark:text-blue-400 hover:text-blue-600 dark:hover:text-blue-300 dark-transition">
                                Resource Hub <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                        <hr class="border-gray-300 dark:border-gray-600">
                        <div class="my-3">
                            <a href="{{ route('admin.playbooks.index') }}"
                                class="link-dark dark:text-blue-400 hover:text-blue-600 dark:hover:text-blue-300 dark-transition">
                                Playbook Downloads <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                        <hr class="border-gray-300 dark:border-gray-600">
                        {{-- @endcan --}}


                        {{-- @can('view_students') --}}
                        <div class="my-3">
                            <a href="{{ route('admin.students.index') }}"
                                class="link-dark dark:text-blue-400 hover:text-blue-600 dark:hover:text-blue-300 dark-transition">
                                View Students <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                        <hr class="border-gray-300 dark:border-gray-600">
                        {{-- @endcan --}}

                        @if (auth()->user() && auth()->user()->hasRole('admin'))
                            <br><br><br>
                            <div class="my-3">
                                <a href="{{ route('admin.brn.index') }}"
                                    class="link-dark dark:text-blue-400 hover:text-blue-600 dark:hover:text-blue-300 dark-transition">
                                    BRN Interest Applications <i class="fa fa-arrow-right"></i>
                                </a>
                            </div>
                            <hr class="border-gray-300 dark:border-gray-600">
                            <div class="my-3">
                                <a href="{{ route('admin.clicks.report') }}"
                                    class="link-dark dark:text-blue-400 hover:text-blue-600 dark:hover:text-blue-300 dark-transition">
                                    Pages Report (Clicks) <i class="fa fa-arrow-right"></i>
                                </a>
                            </div>
                            <hr class="border-gray-300 dark:border-gray-600">
                            <div class="my-3">
                                <a href="{{ route('admin.staff.index') }}"
                                    class="link-dark dark:text-blue-400 hover:text-blue-600 dark:hover:text-blue-300 dark-transition">
                                    Manage Staff <i class="fa fa-arrow-right"></i>
                                </a>
                            </div>
                        @endif
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
