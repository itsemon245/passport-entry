@extends('layouts.app', ['title' => 'Dashboard'])

@section('content')
    <table class="w-full">
        <thead>
            <tr
                class="text-xs font-bold tracking-wide text-left text-gray-700 uppercase border-b dark:border-gray-800 bg-gray-100 dark:text-gray-400 dark:bg-gray-800">
                <th rowspan="2" colspan="2"
                    class=" text-center px-2 md:px-10 border border-slate-600 py-2 md:py-4 text-sm md:text-xl capitalize max-w-max">
                    Monthly Short Report <span class="hidden lg:inline-block">-</span>
                    <div class="text-purple-600 lg:inline-block">{{ now()->format('F') . ', ' . now()->format('Y') }}
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
                        {{ $value }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
