@extends('layouts.app', ['title' => 'Update Payment'])

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
  
@endsection

@section('content')
    <div class="w-full overflow-hidden rounded-lg shadow-xs bg-white p-4">
        <div class="w-full overflow-x-auto">
            <!-- Modal body -->


            
            <form action="{{ route('payment.update', $payment) }}" class="container px-4" id="hx-edit-form" method="post">
                @csrf
                @method('PUT')
                <div class="p-4 md:p-5 space-y-4">

                    <div class="grid gap-6 mb-6 md:grid-cols-2">
                        <div>
                            <label for="date"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date</label>
                            <div class="relative flex items-center">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                    </svg>
                                </div>
                                <input id="date" name="date" type="date" value="{{ $payment->created_at->format('Y-m-d') }}"
                                    class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                                    placeholder="Select date">
                            </div>
                        </div>
                        <div>
                            <label for="amount"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Amount</label>
                            <input type="number" id="amount" name="amount" value="{{ $payment->amount }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                                placeholder="Amount">
                            @error('amount')
                                <div class="text-rose-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="">
                            <label for="client"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Client
                                Name</label>
                            <select id="client" name="user_id" class="tail-select !w-full">
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}" @selected($payment->user_id == $client->id)>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-notify::notify />

                            @error('user_id')
                                <div class="text-rose-500 text-sm">{{ $message }}</div>
                            @enderror

                        </div>
                        <div>
                            <label for="client" class="text-sm font-medium text-gray-900 dark:text-white">Select
                                Payment Method</label>
                            <select name="payment_method"
                                class="mt-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500">
                                <option value="cash" @selected($payment->payment_method == 'cash')>Cash</option>
                                <option value="bkash" @selected($payment->payment_method == 'bkash')>Bkash</option>
                                <option value="discount" @selected($payment->payment_method == 'discount')>Discount</option>
                            </select>
                        </div>
                    </div>

                </div>
                <!-- Modal footer -->
                <div
                    class="flex justify-end gap-4 items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <a href="{{route('payment.index')}}" class="flex gap-2 items-center justify-between w-max px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-rose-500 border border-transparent rounded-lg active:bg-rose-600 hover:bg-rose-700 focus:outline-none focus:shadow-outline-rose">Cancel</a>
                    <x-btn-primary type="submit">Submit</x-btn-primary>
                </div>
            </form>

        </div>
    </div>
@endsection
