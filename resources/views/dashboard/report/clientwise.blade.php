@extends('layouts.app', ['title' => 'Client Report'])

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/print.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tail-select.css') }}">
    <style>
        @media print {
            .border {
                border-color: black;
            }

            .border-t {
                border-color: black;
            }

            ::-webkit-scrollbar {
                display: none;
            }
        }

        @page {
            margin: 1rem auto;
        }
    </style>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tail.select.js@1.0.0/js/tail.select.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            tail.select('.tail-select')

            document.addEventListener('htmx:afterSettle', () => {
                // tail.select('.tail-select').init()
            });
        });
    </script>
@endsection
@section('actions')
@endsection

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 justify-center items-end mb-2 print:hidden">
        <div></div>
        @if (auth()->user()->is_admin)
            <div class="col-span-2">
                <label for="client" class="text-sm font-medium text-gray-900 dark:text-white">Select Client
                    Name</label>
                <select hx-get="{{ route('report.client') }}" hx-include=".date-from, .date-to" hx-target="#hx-result-table"
                    hx-select="#hx-result-table" hx-swap="outerHTML" id="client" name="user_id"
                    class="tail-select !w-max user-id">
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}" @selected($user->id == $client->id)>{{ $client->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @else
            <div class="col-span-2"></div>
            <input type="hidden" class="user-id" name="user_id" value="{{ auth()->id() }}">
        @endif

        <div></div>
        <div></div>
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
                <input hx-get="{{ route('report.client') }}" hx-include=".user-id, .date-to" hx-target="#hx-result-table"
                    hx-select="#hx-result-table" hx-swap="outerHTML" id="date_from" name="date_from" type="date"
                    value="{{ request()->query('date_from') ??today('Asia/Dhaka')->subDays(today()->day - 1)->format('Y-m-d') }}"
                    class="date-from bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Select date From">
            </div>
        </div>
        <div class="print:hidden">
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
                <input hx-get="{{ route('report.client') }}" hx-include=".user-id, .date-from" hx-target="#hx-result-table"
                    hx-select="#hx-result-table" hx-swap="outerHTML" id="date_to" name="date_to" type="date"
                    value="{{ request()->query('date_to') ?? today('Asia/Dhaka')->format('Y-m-d') }}"
                    class="date-to bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Select date To">
            </div>
        </div>
        <x-btn-primary class="print:hidden" x-data @click="window.print()">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18zm-3 0h.008v.008H15z" />
            </svg>
            Print</x-btn-primary>


    </div>
    <div class="print:h-screen print:w-full">
        <div id="hx-result-table" class="print:my-auto">
            <h4 class="font-bold text-md text-center mt-2 mb-4 print:mb-1">{{ $user->name }}'s Report</h4>
            <div class="text-center font-medium mb-4 italic hidden print:block">(From
                {{ \Carbon\Carbon::parse($dateFrom)->format('F d, Y') }}
                to
                {{ \Carbon\Carbon::parse($dateTo)->format('F d, Y') }})</div>
            <div class="w-full rounded-lg print:rounded-none shadow-xs print:shadow-none">
                <div class="w-full">
                    <table class="print:w-max print:mx-auto w-full" id="printable">
                        <thead>
                            <tr
                                class="text-xs font-semibold tracking-wide text-left text-gray-700 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                                <th rowspan="2" class="px-4 text-center border py-1 print:px-2">No.</th>
                                <th rowspan="2" class="px-4 text-center border py-1 print:px-2">Date</th>
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
                            @php
                                $key = 0;
                            @endphp
                            @forelse ($entries as $date => $items)
                                <tr class="text-gray-700 dark:text-gray-400">
                                    <td class="align-middle text-center border">{{ ++$key }}</td>
                                    <td class="px-4 py-1 print:px-2 border">
                                        {{ \Carbon\Carbon::parse($date)->format('d F, Y') }}
                                    </td>
                                    <td class="px-4 py-1 print:px-2 text-sm capitalize border text-center">
                                        {{ count($items['channel'] ?? []) }}
                                    </td>
                                    <td class="px-4 py-1 print:px-2 text-sm capitalize border text-center">
                                        {{ count($items['general'] ?? []) }}
                                    </td>
                                    <td class="text-sm  border">
                                        @isset($items['channel'])
                                            <table class="w-full">
                                                @foreach ($items['channel'] as $i => $entry)
                                                    <tr>
                                                        <td
                                                            class="{{ count($items['channel']) > 1 && $i != 0 ? 'border-t' : '' }}  text-center px-4 py-1 print:px-0">
                                                            {{ $entry->application_id }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        @else
                                            <div class="text-center font-bold">-</div>
                                        @endisset
                                    </td>
                                    <td class="text-sm border">
                                        @isset($items['channel'])
                                            <table class=" w-full">
                                                @foreach ($items['channel'] as $i => $entry)
                                                    <tr>
                                                        <td
                                                            class="{{ count($items['channel']) > 1 && $i != 0 ? 'border-t' : '' }}  text-center px-4 py-1 print:px-0">
                                                            {{ $entry->police_station }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        @else
                                            <div class="text-center font-bold">-</div>
                                        @endisset
                                    </td>
                                </tr>
                            @empty
                                <x-tr.no-records colspan="7" />
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/print.min.js') }}"></script>
@endsection
