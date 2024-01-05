@extends('layouts.app', ['title' => 'Dashboard'])

@section('content')
    <div class="w-full overflow-hidden">
        <div class="flex justify-center overflow-x-auto">
            <table class="max-w-max rounded-lg whitespace-no-wrap">
                <thead>
                    <tr
                        class="text-xs font-bold tracking-wide text-left text-gray-700 uppercase border-b dark:border-gray-800 bg-gray-100 dark:text-gray-400 dark:bg-gray-800">
                        <th rowspan="2" colspan="2"
                            class="px-10 border border-slate-600 py-4 text-xl capitalize max-w-max">Monthly Short Report -
                            <span class="text-purple-600">{{ now()->format('F') . ', ' . now()->format('Y') }}</span> </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key => $value)
                        <tr
                            class="text-xs font-bold tracking-wide text-left text-gray-700 uppercase border-b dark:border-gray-800 bg-gray-100 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-10 border border-slate-600 py-1 text-lg capitalize max-w-max">
                                {{ $key }}</th>
                            <td class="px-10 text-center border border-slate-600 py-1 text-lg">
                                {{ $value }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endsection
