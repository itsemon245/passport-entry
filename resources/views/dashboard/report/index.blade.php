@extends('layouts.app', ['title' => 'Report'])

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/print.min.css') }}">
@endsection
@section('actions')
@endsection

@section('content')
    <div class="flex gap-2 2xl:gap-8 justify-center items-center mb-2 lg:-mt-12 !print:hidden">
        <a href="{{ route('report.client')}}"
            role="button"
            class="flex gap-2 items-center justify-between w-max px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue max-md:mb-5 md:mt-7 md:block">Client Wise</a>
        <form x-ref="form"
            class="flex max-sm:flex-col max-md:flex-wrap  gap-2 2xl:gap-8 justify-center items-center !print:hidden"
            action="{{ route('report.index') }}" method="get" x-data="{ dateFrom: '{{ today('Asia/Dhaka')->subDays(today()->day - 1)->format('Y-m-d') }}', dateTo: '{{ today('Asia/Dhaka')->format('Y-m-d') }}' }">

            <div class="print:hidden">
                <label for="date_from" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date
                    From</label>
                <div class="relative flex items-center">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                        </svg>
                    </div>
                    <input
                        @change="dateFrom = $el.value; $refs.print.href = `/dashboard/report/print?date_from=${dateFrom}&date_to=${dateTo}`"
                        id="date_from" name="date_from" type="date"
                        value="{{ request()->query('date_from') ??today('Asia/Dhaka')->subDays(today()->day - 1)->format('Y-m-d') }}"
                        class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Select date From">
                </div>
            </div>
            <div>
                <label for="date_to" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date
                    To</label>
                <div class="relative flex items-center">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                        </svg>
                    </div>
                    <input
                        @change="dateTo = $el.value; $refs.print.href = `/dashboard/report/print?date_from=${dateFrom}&date_to=${dateTo}`"
                        id="date_to" name="date_to" type="date"
                        value="{{ request()->query('date_to') ?? today('Asia/Dhaka')->format('Y-m-d') }}"
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Select date To">
                </div>
            </div>
            <x-btn-primary class="md:mt-7">Refresh</x-btn-primary>
        </form>

        @php
            $dateFrom = empty(request()->query('date_from'))
                ? today('Asia/Dhaka')
                    ->subDays(today('Asia/Dhaka')->day - 1)
                    ->format('Y-m-d')
                : request()->query('date_from');
            $dateTo = empty(request()->query('date_to')) ? today('Asia/Dhaka')->format('Y-m-d') : request()->query('date_to');
        @endphp

        <a x-ref="print" target="_blank" href="{{ route('report.print') . "?date_from={$dateFrom}&date_to={$dateTo}" }}"
            role="button"
            class="flex gap-2 items-center justify-between w-max px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue max-md:mb-5 md:mt-7 md:block">Print</a>
        {{-- <a x-ref="print" href="{{route('report.download.csv')."?date_from={$dateFrom}&date_to={$dateTo}"}}" role="button" class="flex gap-2 items-center justify-between w-max px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-emerald-600 border border-transparent rounded-lg active:bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:shadow-outline-blue max-md:mb-5 md:mt-7 md:block">Download</a> --}}
    </div>
    <div class="w-full overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap print:w-screen" id="printable">
                <thead>
                    <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-700 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th rowspan="2" class="px-4 text-center border py-1 print:px-2">No.</th>
                        <th rowspan="2" class="px-4 text-center border py-1 print:px-2">Client Name</th>
                        <th colspan="2" class="px-4 text-center border py-1 print:px-2">Survey</th>
                        <th rowspan="2" class="px-4 text-center border py-1 print:px-2">Application ID</th>
                        <th rowspan="2" class="px-4 text-center border py-1 print:px-2">Police Station</th>
                    </tr>
                    <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 text-center border py-1 print:px-2">Channel</th>
                        <th class="px-4 text-center border py-1 print:px-2">General</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    @forelse ($clients as $key => $client)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="align-middle text-center border">{{ ++$key }}</td>
                            <td class="px-4 py-1 print:px-2 border">
                                <div class="flex items-center text-sm">
                                    <!-- Avatar with inset shadow -->
                                    <div class="relative hidden w-8 h-8 mr-3 rounded-full md:block">
                                        <img class="object-cover w-full h-full rounded-full" src="{{ $client->avatar }}"
                                            alt="" loading="lazy" />
                                        <div class="absolute inset-0 rounded-full shadow-inner" aria-hidden="true"></div>
                                    </div>
                                    <div>
                                        <p class="font-semibold">{{ $client->name }}</p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">
                                            {{ $client->username }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-1 print:px-2 text-sm capitalize border text-center">
                                {{ $client->channel_count }}
                            </td>
                            <td class="px-4 py-1 print:px-2 text-sm capitalize border text-center">
                                {{ $client->general_count }}
                            </td>
                            <td class="text-sm  border">
                                @foreach ($client->entries as $i => $entry)
                                    <table
                                        class="{{ $client->entries->count() == 1 || $i == 0 ? '' : 'border-t' }} w-full">
                                        <tr>
                                            <td class="text-center px-4 py-1 print:px-2">{{ $entry->application_id }}</td>
                                        </tr>
                                    </table>
                                @endforeach
                            </td>
                            <td class="text-sm border">
                                @foreach ($client->entries as $i => $entry)
                                    <table
                                        class="{{ $client->entries->count() == 1 || $i == 0 ? '' : 'border-t' }} w-full">
                                        <tr>
                                            <td class="text-center px-4 py-1 print:px-2">{{ $entry->police_station }}</td>
                                        </tr>
                                    </table>
                                @endforeach
                            </td>
                        </tr>
                    @empty
                        <x-tr.no-records colspan="7" />
                    @endforelse
                </tbody>
            </table>
        </div>





        {{-- pagination --}}
        {{-- <div
            class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
            <span class="flex items-center col-span-3">
                Showing 21-30 of 100
            </span>
            <span class="col-span-2"></span>
            <!-- Pagination -->
            <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                <nav aria-label="Table navigation">
                    <ul class="inline-flex items-center">
                        <li>
                            <button class="px-3 py-1 rounded-md rounded-l-lg focus:outline-none focus:shadow-outline-purple"
                                aria-label="Previous">
                                <svg aria-hidden="true" class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                    <path
                                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                        clip-rule="evenodd" fill-rule="evenodd"></path>
                                </svg>
                            </button>
                        </li>
                        <li>
                            <button class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple">
                                1
                            </button>
                        </li>
                        <li>
                            <button class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple">
                                2
                            </button>
                        </li>
                        <li>
                            <button
                                class="px-3 py-1 text-white transition-colors duration-150 bg-purple-600 border border-r-0 border-purple-600 rounded-md focus:outline-none focus:shadow-outline-purple">
                                3
                            </button>
                        </li>
                        <li>
                            <button class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple">
                                4
                            </button>
                        </li>
                        <li>
                            <span class="px-3 py-1">...</span>
                        </li>
                        <li>
                            <button class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple">
                                8
                            </button>
                        </li>
                        <li>
                            <button class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple">
                                9
                            </button>
                        </li>
                        <li>
                            <button class="px-3 py-1 rounded-md rounded-r-lg focus:outline-none focus:shadow-outline-purple"
                                aria-label="Next">
                                <svg class="w-4 h-4 fill-current" aria-hidden="true" viewBox="0 0 20 20">
                                    <path
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" fill-rule="evenodd"></path>
                                </svg>
                            </button>
                        </li>
                    </ul>
                </nav>
            </span>
        </div> --}}
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/print.min.js') }}"></script>
@endsection
