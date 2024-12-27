<form id="hx-edit-form" action="{{ route('entry.update', $entry) }}" method="post" method="post">
    @csrf
    @method('put')
    <div class="p-4 md:p-5 space-y-4">

        <div class="grid gap-6 mb-6 md:grid-cols-2">
            <div class="col-span-2">
                <div class="text-center">
                    <h2 class="text-lg font-bold text-purple-600 capitalize">Update {{$entry->doc_type}} Entry</h2>
                </div>
            </div>
            @if ($entry->doc_type == 'channel')
            <div>
                <label for="application_id"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Application
                    ID</label>
                <input type="text" id="application_id" name="application_id" value="{{ $entry->application_id }}"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Application ID">
            </div>
            @else
            <div>
                <label for="number_of_docs" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Number
                    of
                    Documents</label>
                <input disabled value="1" type="number" id="number_of_docs" name="number_of_docs"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Number of Documents">
            </div>
            @endif


            <div class="">
                <label for="client" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Client
                    Name</label>
                <select id="client" name="user_id" class="tail-select w-full">
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}" @selected($client->id == $entry->user_id)>{{ $client->name }}
                        </option>
                    @endforeach
                </select>

            </div>
            <div class="">
                <label for="police_station" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Police
                    Station</label>
                <select id="police_station" name="police_station" class="tail-select !w-full">
                    @foreach (getPoliceStations() as $station)
                        <option value="{{ $station->name }}" @selected($station->name == $entry->police_station)>{{ $station->name }}
                        </option>
                    @endforeach
                </select>

            </div>
            <div>
                <label for="time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Time</label>
                <input type="time" x-init="function() {
                    let d = (new Date().getHours()).toString()
                    let m = (new Date().getMinutes()).toString()
                    d = d.length < 2 ? '0' + d : d
                    m = m.length < 2 ? '0' + m : m

                    $el.value = d + ':' + m

                }" id="time" name="time" value="{{ $entry->time }}"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Time">
            </div>

            <div class="col-span-full">
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
                    <input id="date" name="date" type="date" value="{{ $entry->date }}"
                                                             class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                             placeholder="Select date">
                </div>
            </div>
            <div class="col-span-full">
                <label for="remarks"
                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Remarks</label>
                <select id="remarks" name="remarks" class="select">
                    <option value="" disabled>Select Remarks</option>
                    <option value="" @selected(!$entry->remarks)>No remarks</option>
                    <option value="negative" @selected($entry->remarks == 'negative')>Negative</option>
                    <option value="second_time" @selected($entry->remarks == 'second_time')>Second Time</option>
                </select>
            </div>

        </div>


    </div>
    <!-- Modal footer -->
    <div class="flex justify-end gap-4 items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
        <x-btn-danger data-modal-hide="create-modal" type="button">Cancel</x-btn-danger>
        <x-btn-primary type="submit">Update</x-btn-primary>
    </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/tail.select.js@1.0.0/js/tail.select.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        tail.select('.tail-select')

        document.addEventListener('htmx:afterSettle', () => {
            tail.select('.tail-select').reload()
        });
        console.log(tail);
    });
</script>
