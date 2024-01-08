@extends('layouts.app', ['title' => 'Payment'])

@section('styles')
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


@section('content')
    <div class="w-full overflow-hidden rounded-lg shadow-xs bg-white p-4">
        <div class="w-full overflow-x-auto">
            <form action="{{ route('payment.store') }}" method="post">
                @csrf
                <div class="max-w-lg mx-auto">
                    <div class="flex gap-6 items-center justify-center mb-4">
                        <label for="client" class="text-sm font-medium text-gray-900 dark:text-white">Select Client
                            Name</label>
                        <select hx-get="{{ route('payment.index') }}" hx-target="#hx-target" hx-select="#hx-target"
                            hx-include="[type='date']" id="client" name="user_id"
                            class="tail-select !w-max payment-data">
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}" @selected(request()->query('user_id') == $client->id)>{{ $client->name }}
                                </option>
                            @endforeach
                        </select>


                    </div>
                </div>


                <div id="hx-target">
                    <div class="flex gap-12 justify-center items-center mb-8">
                        <div>
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
                                <input id="date_from" name="date_from" type="date"
                                    value="{{ today()->subDays(today()->day - 1)->format('Y-m-d') }}"
                                    class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
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
                                <input id="date_to" name="date_to" type="date"
                                    value="{{ today('Asia/Dhaka')->format('Y-m-d') }}"
                                    class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Select date To">
                            </div>
                        </div>
                    </div>

                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr
                                class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                                <th class="px-4 py-3 text-center border border-slate-300">Total Balance</th>
                                <th class="px-4 py-3 text-center border border-slate-300">Total Paid</th>
                                <th class="px-4 py-3 text-center border border-slate-300">Total Due</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                            <tr class="text-gray-700 dark:text-gray-400 ">
                                <input type="hidden" name="total_due" value="{{ $data->total_due ?? 0 }}">

                                <td class="px-4 py-3 text-lg capitalize text-center p-4 border border-slate-300">
                                    {{ $data->total_balance ?? 0 }} <x-heroicon-o-currency-bangladeshi
                                        class="w-5 h-5 inline mb-1"></x-heroicon-o-currency-bangladeshi></td>
                                <td class="px-4 py-3 text-lg capitalize text-center p-4 border border-slate-300">
                                    {{ $data->total_paid ?? 0 }} <x-heroicon-o-currency-bangladeshi
                                        class="w-5 h-5 inline mb-1"></x-heroicon-o-currency-bangladeshi></td>
                                <td class="px-4 py-3 text-lg capitalize text-center p-4 border border-slate-300">
                                    {{ $data->total_due ?? 0 }} <x-heroicon-o-currency-bangladeshi
                                        class="w-5 h-5 inline mb-1"></x-heroicon-o-currency-bangladeshi></td>
                            </tr>
                        </tbody>

                    </table>

                    <hr class="border-2 border-slate-300 my-10">


                    <table class="w-full whitespace-no-wrap mb-3">
                        <thead>
                            <tr
                                class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                                <th class="px-4 py-3 text-center border border-slate-300" colspan="2">Number of Docs</th>
                                <th class="px-4 py-3 text-center border border-slate-300" colspan="2">Payment Amount</th>
                                <th class="px-4 py-3 text-center border border-slate-300">Total Payable Amount</th>
                                <th class="px-4 py-3 text-center border border-slate-300">Now Paying</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                            <tr class="text-gray-700 dark:text-gray-400 ">

                                <td class="px-4 py-1 text-sm capitalize text-center border border-slate-300">Channel</td>
                                <td class="px-4 py-1 text-sm capitalize text-center border border-slate-300">General</td>
                                <td class="px-4 py-1 text-sm capitalize text-center border border-slate-300">Channel Payment
                                </td>
                                <td class="px-4 py-1 text-sm capitalize text-center border border-slate-300">General Payment
                                </td>
                                <td class="px-4 py-1 text-lg capitalize text-center border border-slate-300" rowspan="2">
                                    {{ $data->total_due ?? 0 }} <x-heroicon-o-currency-bangladeshi
                                        class="w-5 h-5 inline mb-1"></x-heroicon-o-currency-bangladeshi>
                                </td>
                                <td class="px-4 py-1 text-lg capitalize text-center border border-slate-300" rowspan="2">
                                    <input type="number" name="amount"
                                        class="payment-data bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Paying Amount">
                                </td>
                            </tr>
                            <tr class="text-gray-700 dark:text-gray-400 ">
                                <td class="px-4 h-7 text-lg capitalize text-center border border-slate-300">
                                    {{ $data->channel_entry ?? 0 }} <x-heroicon-o-currency-bangladeshi
                                        class="w-5 h-5 inline mb-1"></x-heroicon-o-currency-bangladeshi> </td>
                                <td class="px-4 h-7 text-lg capitalize text-center border border-slate-300">
                                    {{ $data->general_entry ?? 0 }} <x-heroicon-o-currency-bangladeshi
                                        class="w-5 h-5 inline mb-1"></x-heroicon-o-currency-bangladeshi> </td>
                                <td class="px-4 h-7 text-lg capitalize text-center border border-slate-300">
                                    {{ $data->channel_payment ?? 0 }} <x-heroicon-o-currency-bangladeshi
                                        class="w-5 h-5 inline mb-1"></x-heroicon-o-currency-bangladeshi> </td>
                                <td class="px-4 h-7 text-lg capitalize text-center border border-slate-300">
                                    {{ $data->general_payment ?? 0 }} <x-heroicon-o-currency-bangladeshi
                                        class="w-5 h-5 inline mb-1"></x-heroicon-o-currency-bangladeshi> </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="flex justify-end">
                        <div class="flex gap-3 items-end">
                            <div>
                                <label for="client" class="text-sm font-medium text-gray-900 dark:text-white">Select
                                    Payment Method</label>
                                <select
                                    name="payment_method"
                                    class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                                    <option value="cash" selected>Cash</option>
                                    <option value="bkash">Bkash</option>
                                </select>
                            </div>
                            <x-btn-primary type="submit">
                                Save Payment
                            </x-btn-primary>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
