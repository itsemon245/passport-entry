@extends('layouts.app', ['title' => 'Dashboard'])

@section('content')
    <table class="w-full">
        <thead>
            <tr
                class="text-xs font-bold tracking-wide text-left text-gray-700 uppercase border-b dark:border-gray-800 bg-gray-100 dark:text-gray-400 dark:bg-gray-800">
                <th colspan="2"
                    class=" text-center px-2 md:px-10 border border-slate-600 py-2 md:py-4 text-sm md:text-xl capitalize max-w-max">
                    <div>
                        <label for="date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Choose a
                            month</label>
                        <form action="{{ route('dashboard') }}" method="GET"
                            class="relative flex w-max mx-auto items-center">
                            <div class="">
                                <select onchange="this.form.submit()" id="months" name="month"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    @foreach (range(1, now('Asia/Dhaka')->month) as $i => $m)
                                        <option value="{{ $m }}" @selected(!empty(request()->query('month')) ? request()->query('month') == $m : now('Asia/Dhaka')->month == $m)>
                                            {{ now('Asia/Dhaka')->addMonths($m - 2)->format('F') }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                </th>
            </tr>
            <tr
                class="text-xs font-bold tracking-wide text-left text-gray-700 uppercase border-b dark:border-gray-800 bg-gray-100 dark:text-gray-400 dark:bg-gray-800">
                <th colspan="2"
                    class=" text-center px-2 md:px-10 border border-slate-600 py-2 md:py-4 text-sm md:text-xl capitalize max-w-max">
                    Monthly Short Report <span class="hidden lg:inline-block">-</span>
                    <div class="text-purple-600 lg:inline-block">{{ $monthName . now('Asia/Dhaka')->format(', Y') }}
                    </div>
                </th>
            </tr>
        </thead>
        <tbody class="border-t">
            @foreach ($data as $key => $value)
                <tr
                    class="border-t font-medium md:font-semibold text-wrap text-left text-gray-700 border dark:border-gray-800 bg-gray-100 dark:text-gray-400 dark:bg-gray-800">
                    <td class="px-1 md:px-4 lg:px-10 border border-slate-600 py-1 text-xs md:text-lg capitalize">
                        {{ $key }}</td>
                    <td class="px-1 md:px-4 lg:px-10 text-center border border-slate-600 py-1 text-xs md:text-lg">
                        {{ formatNumber($value) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
