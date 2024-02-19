@extends('layouts.admin.layout')

@section('content')
    @include('layouts.admin.components.sidebar')

    <div class="p-4 sm:ml-64">

        <div>
            <div class="flex justify-between">
                <h1 class="text-md font-medium">Blogs aangemaakt in {{$currentMonthName}}</h1>
                <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">{{$currentMonthName ? $currentMonthName : "Selecteer een maand"}} <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                    </svg>
                </button>
                
                <!-- Dropdown menu -->
                <div id="dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                        @foreach ($blogsYear as $month)
                            <li>
                                <a href="{{route('admin.dashboard.month', ['month' => $month->month])}}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">{{ $month->month }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            
    
            <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Geregistreerde auteurs</dt>
                    <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{$userCount}}</dd>
                </div>
                <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Geplaatste blogs</dt>
                    <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{$blogsCount}}</dd>
                </div>
            </dl>
        </div>

        <div class="w-full bg-white rounded-lg shadow p-4 mt-5 md:p-6">
            <div class="flex justify-between pb-4 mb-4 border-b border-gray-200">
                <div class="flex items-center">
                    <div>
                        <p class="text-sm font-medium">Blogs aangemaakt afgelopen jaar</p>
                    </div>
                </div>
            </div>

            <div id="column-chart"></div>
        </div>



    </div>
@endsection

@section('after_scripts')
    <script>
        const options = {
            colors: ["#1A56DB", "#FDBA8C"],
            series: [{
                name: "Aangemaakt",
                color: "#1A56DB",
                data: [
                    @foreach ($blogsYear as $blog)
                        {
                            x: "{{ $blog->month }}",
                            y: {{ $blog->count }}
                        },
                    @endforeach
                ],
            }, ],
            chart: {
                type: "bar",
                height: "320px",
                fontFamily: "Inter, sans-serif",
                toolbar: {
                    show: false,
                },
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: "70%",
                    borderRadiusApplication: "end",
                    borderRadius: 8,
                },
            },
            tooltip: {
                shared: true,
                intersect: false,
                style: {
                    fontFamily: "Inter, sans-serif",
                },
            },
            states: {
                hover: {
                    filter: {
                        type: "darken",
                        value: 1,
                    },
                },
            },
            stroke: {
                show: true,
                width: 0,
                colors: ["transparent"],
            },
            grid: {
                show: false,
                strokeDashArray: 4,
                padding: {
                    left: 2,
                    right: 2,
                    top: -14
                },
            },
            dataLabels: {
                enabled: false,
            },
            legend: {
                show: false,
            },
            xaxis: {
                floating: false,
                labels: {
                    show: true,
                    style: {
                        fontFamily: "Inter, sans-serif",
                        cssClass: 'text-xs font-normal fill-gray-500 dark:fill-gray-400'
                    }
                },
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false,
                },
            },
            yaxis: {
                show: false,
            },
            fill: {
                opacity: 1,
            },
        }

        if (document.getElementById("column-chart") && typeof ApexCharts !== 'undefined') {
            const chart = new ApexCharts(document.getElementById("column-chart"), options);
            chart.render();
        }
    </script>
@endsection
