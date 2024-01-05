@extends('layouts.app', ['title' => 'Entry'])

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/tail-select.css') }}">
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tail.select.js@1.0.0/js/tail.select.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            tail.select('.tail-select')
        });
    </script>
@endsection

@section('actions')
    <x-btn-primary data-modal-target="create-modal" data-modal-toggle="create-modal">
        <x-heroicon-o-plus class="w-5 h-5" />
        New Entry
    </x-btn-primary>
    <!-- Main modal -->
    <div id="create-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        New Entry
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="create-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form action="{{ route('entry.store') }}" method="post">
                    @csrf
                    <div class="p-4 md:p-5 space-y-4">

                        <div class="grid gap-6 mb-6 md:grid-cols-2" x-data="{ isChannel: true }">

                            <div role="button" :class="{ 'bg-purple-600': isChannel }"
                                class="flex max-w-max items-center px-4 border border-gray-200 rounded-lg dark:border-gray-700">
                                <input @change="isChannel= true" id="bordered-radio-1" type="radio" value="true"
                                    name="is_channel" checked
                                    class="hidden w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="bordered-radio-1"
                                    :class="{ 'text-gray-100': isChannel, 'text-gray-900': !isChannel }"
                                    class="w-full py-2 text-sm font-medium dark:text-gray-300">Channel</label>
                            </div>
                            <div role="button" :class="{ 'bg-purple-600': !isChannel }"
                                class="flex justify-self-end max-w-max items-center px-4 border border-gray-200 rounded-lg dark:border-gray-700">
                                <input @change="isChannel = false" id="bordered-radio-2" type="radio" value="false"
                                    name="is_channel"
                                    class="hidden w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="bordered-radio-2"
                                    :class="{ 'text-gray-100': !isChannel, 'text-gray-900': isChannel }"
                                    class="w-full py-2 text-sm font-medium dark:text-gray-300">General</label>
                            </div>

                            <div x-show="isChannel">
                                <label for="application_id"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Application
                                    ID</label>
                                <input type="text" id="application_id" name="application_id"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Application ID">
                            </div>
                            <div x-show="!isChannel">
                                <label for="number_of_docs"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Number of
                                    Documents</label>
                                <input type="number" id="number_of_docs" name="number_of_docs"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Number of Documents">
                            </div>

                            <div class="">
                                <label for="client"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Client Name</label>
                                <select id="client" name="user_id" class="tail-select !w-full">
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->name }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="">
                                <label for="police_station"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Police Station</label>
                                <select id="police_station" name="police_station" class="tail-select !w-full">
                                    @foreach (getPoliceStations() as $station)
                                        <option value="{{ $station->name }}">{{ $station->name }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                            <div>
                                <label for="time"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Time</label>
                                <input type="time" x-init="function() {
                                    let d = (new Date().getHours()).toString()
                                    let m = (new Date().getMinutes()).toString()
                                    d = d.length < 2 ? '0' + d : d
                                    m = m.length < 2 ? '0' + m : m
                                
                                    $el.value = d + ':' + m
                                
                                }" id="time" name="time"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Time">
                            </div>

                            <div class="col-span-2">
                                <div>
                                    <label for="date"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date</label>
                                    <div class="relative flex items-center">
                                        <div
                                            class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                            </svg>
                                        </div>
                                        <input id="date" name="date" type="date"
                                            value="{{ today('Asia/Dhaka')->format('Y-m-d') }}"
                                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Select date">
                                    </div>
                                </div>
                            </div>

                        </div>


                    </div>
                    <!-- Modal footer -->
                    <div
                        class="flex justify-end gap-4 items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <x-btn-danger data-modal-hide="create-modal" type="button">Cancel</x-btn-danger>
                        <x-btn-primary type="submit">Submit</x-btn-primary>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Edit modal -->
    <div id="edit-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        New Entry
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="edit-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form id="hx-edit-form" action="{{ route('entry.store') }}" method="post">
                    @csrf
                    <div class="p-4 md:p-5 space-y-4">

                        <div class="grid gap-6 mb-6 md:grid-cols-2" x-data="{ isChannel: true }">

                            <div role="button" :class="{ 'bg-purple-600': isChannel }"
                                class="flex max-w-max items-center px-4 border border-gray-200 rounded-lg dark:border-gray-700">
                                <input @change="isChannel= true" id="bordered-radio-1" type="radio" value="true"
                                    name="is_channel" checked
                                    class="hidden w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="bordered-radio-1"
                                    :class="{ 'text-gray-100': isChannel, 'text-gray-900': !isChannel }"
                                    class="w-full py-2 text-sm font-medium dark:text-gray-300">Channel</label>
                            </div>
                            <div role="button" :class="{ 'bg-purple-600': !isChannel }"
                                class="flex justify-self-end max-w-max items-center px-4 border border-gray-200 rounded-lg dark:border-gray-700">
                                <input @change="isChannel = false" id="bordered-radio-2" type="radio" value="false"
                                    name="is_channel"
                                    class="hidden w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="bordered-radio-2"
                                    :class="{ 'text-gray-100': !isChannel, 'text-gray-900': isChannel }"
                                    class="w-full py-2 text-sm font-medium dark:text-gray-300">General</label>
                            </div>

                            <div x-show="isChannel">
                                <label for="application_id"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Application
                                    ID</label>
                                <input type="text" id="application_id" name="application_id"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Application ID">
                            </div>
                            <div x-show="!isChannel">
                                <label for="number_of_docs"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Number of
                                    Documents</label>
                                <input type="number" id="number_of_docs" name="number_of_docs"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Number of Documents">
                            </div>

                            <div class="">
                                <label for="client"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Client Name</label>
                                <select id="client" name="user_id" class="tail-select w-full">
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->name }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                            <div>
                                <label for="police_station"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Police
                                    Station</label>
                                <input type="text" id="police_station" name="police_station"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Police Station">
                            </div>
                            <div>
                                <label for="time"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Time</label>
                                <input type="time" x-init="function() {
                                    let d = (new Date().getHours()).toString()
                                    let m = (new Date().getMinutes()).toString()
                                    d = d.length < 2 ? '0' + d : d
                                    m = m.length < 2 ? '0' + m : m
                                
                                    $el.value = d + ':' + m
                                
                                }" id="time" name="time"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Time">
                            </div>

                            <div class="col-span-2">
                                <div>
                                    <label for="date"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date</label>
                                    <div class="relative flex items-center">
                                        <div
                                            class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                            </svg>
                                        </div>
                                        <input id="date" name="date" type="date"
                                            value="{{ today('Asia/Dhaka')->format('Y-m-d') }}"
                                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Select date">
                                    </div>
                                </div>
                            </div>

                        </div>


                    </div>
                    <!-- Modal footer -->
                    <div
                        class="flex justify-end gap-4 items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <x-btn-danger data-modal-hide="edit-modal" type="button">Cancel</x-btn-danger>
                        <x-btn-primary type="submit">Submit</x-btn-primary>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
@endsection
@section('content')
    <div class="w-full overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3 text-center">No.</th>
                        <th class="px-4 py-3">Client Name</th>
                        <th class="px-4 py-3">Document Type</th>
                        <th class="px-4 py-3">Application ID</th>
                        <th class="px-4 py-3">Police Station</th>
                        <th class="px-4 py-3">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    @forelse ($entries as $key => $entry)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="align-middle text-center">{{ ++$key }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center text-sm">
                                    <!-- Avatar with inset shadow -->
                                    <div class="relative hidden w-8 h-8 mr-3 rounded-full md:block">
                                        <img class="object-cover w-full h-full rounded-full"
                                            src="{{ $entry->user->avatar }}" alt="" loading="lazy" />
                                        <div class="absolute inset-0 rounded-full shadow-inner" aria-hidden="true"></div>
                                    </div>
                                    <div>
                                        <p class="font-semibold">{{ $entry->user->name }}</p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">
                                            {{ $entry->user->username }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm capitalize">
                                {{ $entry->doc_type }}
                            </td>
                            <td class="px-4 py-3 text-sm capitalize">
                                {{ $entry->application_id ?? 'Not Applicable' }}
                            </td>
                            <td class="px-4 py-3 text-sm capitalize">
                                {{ $entry->police_station }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-4 text-sm">
                                    <button data-modal-target="edit-modal" data-modal-toggle="edit-modal"
                                        hx-get="{{ route('entry.edit', $entry) }}" hx-transition
                                        hx-target="#hx-edit-form"
                                        class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                                        aria-label="Edit">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                            </path>
                                        </svg>
                                    </button>
                                    <form action="{{ route('entry.destroy', $entry) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                                            aria-label="Delete">
                                            <svg class="w-5 h-5" aria-hidden="true" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <x-tr.no-records colspan="6" />
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/datepicker.min.js"></script>
@endsection
