@extends('layouts.app', ['title' => 'Client Report'])

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/print.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tail-select.css') }}">
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
    <div class="flex gap-2 2xl:gap-8 justify-center items-center mb-2 lg:-mt-12 !print:hidden">

        <div class="">
            <label for="client" class="text-sm font-medium text-gray-900 dark:text-white">Select Client
                Name</label>
            <select hx-get="{{ route('report.client') }}" hx-target="#hx-result-table" hx-select="#hx-result-table"
                hx-swap="outerHTML" id="client" name="user_id" class="tail-select !w-max ">
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}" @selected($user->id == $client->id)>{{ $client->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div id="hx-result-table">
        <h3 class="font-bold text-lg text-center mt-2 mb-4">{{ $user->name }}'s Report</h3>
        <div class="w-full overflow-hidden rounded-lg shadow-xs" >
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap print:w-screen" id="printable">
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
                                                        class="{{ count($items['channel']) > 1 && $i != 0 ? 'border-t' : '' }}  text-center px-4 py-1 print:px-2">
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
                                                        class="{{ count($items['channel']) > 1 && $i != 0 ? 'border-t' : '' }}  text-center px-4 py-1 print:px-2">
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
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/print.min.js') }}"></script>
@endsection
