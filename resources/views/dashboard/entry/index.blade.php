@extends('layouts.app', ['title' => 'Entry'])

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
                let btns = document.querySelectorAll('[type="submit"]')
                btns.forEach(btn => {
                    btn.outerHTML =
                        `<x-btn-primary type="submit">${btn.dataset.type == "search"? "Search": "Submit"}</x-btn-primary>`
                });
                let notifyBtn = $('#laravel-notify').find('button');
                notifyBtn.attr('type', 'button')

                tail.select('.tail-select').reload()
            });
            document.addEventListener('htmx:beforeRequest', () => {

                let btns = document.querySelectorAll('[type="submit"]')
                btns.forEach(btn => {
                    btn.outerHTML = `<x-btn-primary type="submit" disabled>
                                <svg aria-hidden="true" role="status" class="inline w-4 h-4 me-3 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                                </svg>
                            Proccessing...
                            </x-btn-primary>`
                });
            });

            let closeModalBtns = document.querySelectorAll('[data-modal-hide="create-modal"]')
            closeModalBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    location.reload()
                })
            });

        });
    </script>
@endsection

@section('actions')
    @if(auth()->user()->is_admin)
        <div class="flex-grow">
            <x-btn-primary data-modal-target="create-modal" data-modal-toggle="create-modal">
                <x-heroicon-o-plus class="w-5 h-5" />
                    New Entry
            </x-btn-primary>
        </div>

    @endif

    <div x-data="">
        <form class="grid lg:grid-cols-5 gap-4" action="{{ route('entry.index') }}" method="GET"
            hx-target="#hx-search-target" hx-select="#hx-search-target" hx-swap="outerHTML" class="w-full">
            <div class="relative items-center gap-2.5">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3  pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input @change="$el.value = $el.value.trim();" value="{{ request()->query('query') }}" autofocus
                    type="search" id="default-search" name="query"
                    class="block w-full p-[0.6rem] ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Search Application ID or Police Station...">

            </div>
            <div class="">
                <select id="countries" name="doc_type"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option selected value="">Choose Doc Type</option>
                    <option value="channel" @selected(request()->query('doc_type') == 'channel')>Channel</option>
                    <option value="general" @selected(request()->query('doc_type') == 'general')>General</option>
                </select>
            </div>
            <div>
                {{-- <label for="date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date</label> --}}
                <div class="relative flex items-center">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                        </svg>
                    </div>
                    <input id="date" name="date_filter" type="date" value="{{ request()->date_filter }}"
                        class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Select date">
                </div>
            </div>
            <div class="flex gap-4">
                <x-btn-primary data-type="search" type="submit" class="">
                    Submit
                </x-btn-primary>
                <a role="button" href="{{ route('entry.index') }}"
                    class="flex gap-2 items-center justify-between w-max px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Main modal -->
    <div id="create-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true"
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
                <form hx-post="{{ route('entry.store') }}" hx-select="#hx-create-form" hx-target="#hx-create-form"
                    hx-swap="outerHTML" method="post">
                    @csrf
                    <div  class="p-4 md:p-5 space-y-4"  x-data="{ isChannel: '{{ old('is_channel') }}' == 'false' ? false : true }">

                        <div id="hx-create-form" class="grid gap-6 mb-6 md:grid-cols-2">
                            <div role="button" :class="{ 'bg-purple-600': isChannel }"
                                class="flex max-w-max items-center px-4 border border-gray-200 rounded-lg dark:border-gray-700">
                                <input @change="isChannel= true" id="bordered-radio-1" type="radio" value="true"
                                    name="is_channel" @checked(empty(old('is_channel')) || old('is_channel') == 'true')
                                    class="hidden w-4 h-4 text-purple-600 border-gray-300 focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="bordered-radio-1"
                                    :class="{ 'text-gray-100': isChannel, 'text-gray-900': !isChannel }"
                                    class="w-full py-2 text-sm font-medium dark:text-gray-300">Channel</label>
                            </div>
                            <div role="button" :class="{ 'bg-purple-600': !isChannel }"
                                class="flex justify-self-end max-w-max items-center px-4 border border-gray-200 rounded-lg dark:border-gray-700">
                                <input @change="isChannel = false" id="bordered-radio-2" type="radio" value="false"
                                    name="is_channel" @checked(old('is_channel') == 'false')
                                    class="hidden w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="bordered-radio-2"
                                    :class="{ 'text-gray-100': !isChannel, 'text-gray-900': isChannel }"
                                    class="w-full py-2 text-sm font-medium dark:text-gray-300">General</label>
                            </div>

                            <div x-show="isChannel">
                                <label for="application_id"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Application
                                    ID</label>
                                <input type="text" id="application_id" name="application_id"
                                    value="{{ old('application_id') }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                                    placeholder="Application ID">
                                @error('application_id')
                                    <div class="text-rose-500 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            <div x-show="!isChannel">
                                <label for="number_of_docs"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Number of
                                    Documents</label>
                                <input type="number" id="number_of_docs" name="number_of_docs"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                                    placeholder="Number of Documents">
                                @error('number_of_docs')
                                    <div class="text-rose-500 text-sm">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="">
                                <label for="client"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Client
                                    Name</label>
                                <select id="client" name="user_id" class="tail-select !w-full">
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}" @selected(old('user_id') == $client->id || (session('request')['user_id'] ?? '') == $client->id )>
                                            {{ $client->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-notify::notify />

                                @error('user_id')
                                    <div class="text-rose-500 text-sm">{{ $message }}</div>
                                @enderror

                            </div>
                            <div class="">
                                <label for="police_station"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Police
                                    Station</label>
                                <select id="police_station" name="police_station" class="tail-select !w-full">
                                    @foreach (getPoliceStations() as $station)
                                        <option value="{{ $station->name }}"@selected(old('police_station') == $station->name || (session('request')['police_station'] ?? '') == $station->name)>
                                            {{ $station->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('police_station')
                                    <div class="text-rose-500 text-sm">{{ $message }}</div>
                                @enderror

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
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                                    placeholder="Time">
                            </div>

                        </div>
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
                                <input id="date" name="date" type="date"
                                                             value="{{ request()->date ?? today('Asia/Dhaka')->format('Y-m-d') }}"
                                                             class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                             placeholder="Select date">
                            </div>
                        </div>
                        <div hx-swap-oob="true" id="remarks-div" x-show="isChannel">
                            <label for="remarks"
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Remarks</label>
                            <select id="remarks" name="remarks" class="select">
                                <option value="" selected disabled>Select Remarks</option>
                                <option value="">No remarks</option>
                                <option :value="isChannel ? 'negative' : ''">Negative</option>
                                <option :value="isChannel ? 'second_time' : ''">Second Time</option>
                            </select>
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
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Client
                                    Name</label>
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
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 20 20">
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
    <div class="w-full overflow-hidden rounded-lg shadow-xs" id="hx-search-target">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3 text-center">No.</th>
                        <th class="px-4 py-3">Client Name</th>
                        <th class="px-4 py-3">Entry Date</th>
                        <th class="px-4 py-3">Document Type</th>
                        <th class="px-4 py-3">Application ID</th>
                        <th class="px-4 py-3">Police Station</th>
                        @if (auth()->user()->is_admin)
                            <th class="px-4 py-3">Action</th>
                        @endif
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
                            <td class="px-4 text-sm py-3">
                                <div>{{ \Carbon\Carbon::parse($entry->date)->format('d F, Y') }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm capitalize">
                                <div>{{ $entry->doc_type }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm capitalize">
                                {{ $entry->application_id ?? 'Not Applicable' }}
                            </td>
                            <td class="px-4 py-3 text-sm capitalize">
                                {{ $entry->police_station }}
                            </td>
                            @if (auth()->user()->is_admin)
                                <td class="px-4 py-3">
                                <div class="flex items-center space-x-4 text-sm">
                                    <a role="button" data-modal-target="edit-modal" data-modal-toggle="edit-modal"
                                        hx-get="{{ route('entry.edit', $entry->id) }}" hx-transition
                                        hx-target="#hx-edit-form"
                                        class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                                        aria-label="Edit">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                            </path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('entry.destroy', $entry->id) }}" method="post">
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
                        @endif
                        </tr>
                    @empty
                        <x-tr.no-records colspan="7" />
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $entries->withQueryString()->links() }}

        {{-- pagination --}}

    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/datepicker.min.js"></script>
@endsection
