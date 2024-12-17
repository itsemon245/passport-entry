@extends('layouts.app', ['title' => 'Payment History'])

@section('content')
    <div class="flex gap-12 justify-center items-end mb-4 -mt-4">
        <div>
            <label for="date_from" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date
                From</label>
            <div class="relative flex items-center">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                    </svg>
                </div>
                <input hx-get="{{ route('payment.history') }}" hx-select="#hx-payment-history"
                    hx-target="#hx-payment-history" hx-swap="outerHTML" hx-include="[type='date'],[name='user_id']"
                    id="payment_from" name="payment_from" type="date"
                    value="{{ empty(request()->query('payment_from'))? today()->subDays(today()->day - 1)->format('Y-m-d'): request()->query('payment_from') }}"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                    placeholder="Select date From">
            </div>
        </div>

        <div>
            <label for="payment_to" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date
                To</label>
            <div class="relative flex items-center">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                    </svg>
                </div>
                <input hx-get="{{ route('payment.history') }}" hx-select="#hx-payment-history"
                    hx-target="#hx-payment-history" hx-swap="outerHTML" hx-include="[type='date'],[name='user_id']"
                    id="payment_to" name="payment_to" type="date"
                    value="{{ empty(request()->query('payment_to')) ? today('Asia/Dhaka')->format('Y-m-d') : request()->query('payment_from') }}"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                    placeholder="Select date">
            </div>
        </div>
        <a href="{{ route('payment.history') }}"
            class="flex gap-2 items-center justify-between w-max px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">Reset</a>
    </div>
    <div class="w-full overflow-hidden rounded-lg shadow-xs" id="hx-payment-history">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3 text-center">No.</th>
                        <th class="px-4 py-3">Client</th>
                        <th class="px-4 py-3">Payment Date</th>
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
                                <td class="px-4 py-3">
                                    <div class="flex items-center text-sm">
                                        <!-- Avatar with inset shadow -->
                                        <div class="relative hidden w-8 h-8 mr-3 rounded-full md:block">
                                            <img class="object-cover w-full h-full rounded-full"
                                                src="{{ $payment->user->avatar }}" alt="" loading="lazy" />
                                            <div class="absolute inset-0 rounded-full shadow-inner" aria-hidden="true">
                                            </div>
                                        </div>
                                        <div>
                                            <p class="font-semibold">{{ $payment->user->name }}</p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                                {{ $payment->user->username }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 text-sm py-3">
                                    <div>{{ $payment->created_at->format('d F, Y') }}
                                    </div>
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
                                        <a href="{{ route('payment.edit', $payment) }}" {{-- hx-get="{{ route('payment.edit', $payment) }}" hx-transition --}}
                                            hx-target="#hx-edit-form" hx-swap="outerHTML"
                                            class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                                            aria-label="Edit">
                                            <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                                </path>
                                            </svg>
                                        </a>
                                        <a role="button" hx-get="{{ route('payment.delete', $payment) }}" hx-swap="delete"
                                            hx-target="closest tr"
                                            class="w-max flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                                            aria-label="Delete">
                                            <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
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
        {{ $payments->withQueryString()->links() }}
    </div>

@endsection
