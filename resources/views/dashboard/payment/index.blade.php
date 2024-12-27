@extends('layouts.app', ['title' => 'Payment'])

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/tail-select.css') }}">
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tail.select.js@1.0.0/js/tail.select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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
    <a href="{{route('payment.history')}}"
        class="flex gap-2 items-center justify-between w-max px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">History</a>
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
                            <input hx-get="{{ route('payment.index') }}" hx-target="#hx-target" hx-select="#hx-target"
                                hx-include="[type='date'],[name='user_id']" id="date_from" name="date_from" type="date"
                                value="{{ today()->subDays(today()->day - 1)->format('Y-m-d') }}"
                                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
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
                            <input hx-get="{{ route('payment.index') }}" hx-target="#hx-target" hx-select="#hx-target"
                                hx-include="[type='date'],[name='user_id']" id="date_to" name="date_to" type="date"
                                value="{{ today('Asia/Dhaka')->format('Y-m-d') }}"
                                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                                placeholder="Select date To">
                        </div>
                    </div>
                </div>

                <div id="hx-target">

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
                                <th class="px-4 py-3 text-center border border-slate-300" colspan="2">Number of Docs
                                </th>
                                <th class="px-4 py-3 text-center border border-slate-300" colspan="2">Payment Amount
                                </th>
                                <th class="px-4 py-3 text-center border border-slate-300">Total Payable Amount</th>
                                <th class="px-4 py-3 text-center border border-slate-300">Now Paying</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                            <tr class="text-gray-700 dark:text-gray-400 ">

                                <td class="px-4 py-1 text-sm capitalize text-center border border-slate-300">Channel</td>
                                <td class="px-4 py-1 text-sm capitalize text-center border border-slate-300">General</td>
                                <td class="px-4 py-1 text-sm capitalize text-center border border-slate-300">Channel
                                    Payment
                                </td>
                                <td class="px-4 py-1 text-sm capitalize text-center border border-slate-300">General
                                    Payment
                                </td>
                                <td class="px-4 py-1 text-lg capitalize text-center border border-slate-300" rowspan="2">
                                    {{ $data->total_due ?? 0 }} <x-heroicon-o-currency-bangladeshi
                                        class="w-5 h-5 inline mb-1"></x-heroicon-o-currency-bangladeshi>
                                </td>
                                <td class="px-4 py-1 text-lg capitalize text-center border border-slate-300" rowspan="2">
                                    <input type="number" name="amount"
                                        class="payment-data bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
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
                        <label for="date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Payment Date</label>
                        <div class="relative flex items-center">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                </svg>
                            </div>
                            <input
                                id="date"
                                name="date" type="date"
                                value="{{ today('Asia/Dhaka')->format('Y-m-d') }}"
                                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                                placeholder="Select date">
                        </div>
                    </div>
                            <div>
                                <label for="client" class="text-sm font-medium text-gray-900 dark:text-white">Select
                                    Payment Method</label>
                                <select name="payment_method"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500">
                                    <option value="cash" selected>Cash</option>
                                    <option value="bkash">Bkash</option>
                                    <option value="discount">Discount</option>
                                </select>
                            </div>
                            <x-btn-primary type="submit">
                                Save Payment
                            </x-btn-primary>
                        </div>
                    </div>


                    @if (request()->query('user_id'))


                        <div class="my-3" x-data>
                            <h3 class="text-center text-lg font-medium p-4">Payment History</h3>

                            <div class="flex gap-12 justify-center items-center mb-8">
                                <input type="text" class="hidden" name="user_id"
                                    value="{{ request()->query('user_id') }}">
                                <div>
                                    <label for="date_from"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date
                                        From</label>
                                    <div class="relative flex items-center">
                                        <div
                                            class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                            </svg>
                                        </div>
                                        <input hx-get="{{ route('payment.index') }}" hx-select="#hx-payment-history"
                                            hx-target="#hx-payment-history" hx-swap="outerHTML"
                                            hx-include="[type='date'],[name='user_id']" id="payment_from"
                                            name="payment_from" type="date"
                                            value="{{ request()->has('payment_from')? request()->query('payment_from') ??today()->subDays(today()->day - 1)->format('Y-m-d'): request()->query('date_from') }}"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                                            placeholder="Select date From">
                                    </div>
                                </div>

                                <div>
                                    <label for="payment_to"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date
                                        To</label>
                                    <div class="relative flex items-center">
                                        <div
                                            class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                            </svg>
                                        </div>
                                        <input hx-get="{{ route('payment.index') }}" hx-select="#hx-payment-history"
                                            hx-target="#hx-payment-history" hx-swap="outerHTML"
                                            hx-include="[type='date'],[name='user_id']" id="payment_to" name="payment_to"
                                            type="date"
                                            value="{{ request()->has('payment_to') ? request()->query('payment_to') ?? today('Asia/Dhaka')->format('Y-m-d') : request()->query('date_to') }}"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                                            placeholder="Select date">
                                    </div>
                                </div>
                            </div>
                            <table class="w-full whitespace-no-wrap" id="hx-payment-history">
                                <thead>
                                    <tr
                                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                                        <th class="px-4 py-3 text-center">No.</th>
                                        <th class="px-4 py-3">Payment Date</th>
                                        <th class="px-4 py-3">Entry Date</th>
                                        <th class="px-4 py-3">Amount</th>
                                        <th class="px-4 py-3">Payment Method</th>
                                        <th class="px-4 py-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                                    @if ($payments)
                                        @forelse ($payments as $key => $payment)
                                            <tr class="text-gray-700 dark:text-gray-400">
                                                <td class="align-middle text-center">{{ ++$key }}</td>
                                                <td class="px-4 text-sm py-3">
                                                        {{ $payment->date->format('d F, Y') }}
                                                </td>
                                                <td class="px-4 text-sm py-3">
                                                        {{ $payment->created_at->format('d F, Y') }}
                                                </td>
                                                <td class="px-4 text-sm py-3">
                                                    <div>{{ $payment->amount }} <x-heroicon-o-currency-bangladeshi
                                                            class="w-5 h-5 inline mb-1"></x-heroicon-o-currency-bangladeshi>
                                                    </div>
                                                </td>
                                                <td class="px-4 text-sm py-3 capitalize">
                                                    <div>{{ $payment->payment_method }}</div>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <div class="flex gap-4 items-center">
                                                        <a href="{{ route('payment.edit', $payment) }}"
                                                            {{-- hx-get="{{ route('payment.edit', $payment) }}" hx-transition --}} hx-target="#hx-edit-form"
                                                            hx-swap="outerHTML"
                                                            class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                                                            aria-label="Edit">
                                                            <svg class="w-5 h-5" aria-hidden="true" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path
                                                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                                                </path>
                                                            </svg>
                                                        </a>
                                                        <a role="button"
                                                            hx-get="{{ route('payment.delete', $payment) }}"
                                                            hx-swap="delete" hx-target="closest tr"
                                                            class="w-max flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                                                            aria-label="Delete">
                                                            <svg class="w-5 h-5" aria-hidden="true" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </td>

                                            </tr>
                                        @empty
                                            <x-tr.no-records colspan="6" />
                                        @endforelse
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </form>
        </div>
    </div>
@endsection
