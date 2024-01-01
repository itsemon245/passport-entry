 <!-- Modal body -->
 <form id="hx-edit-form" action="{{ route('client.update', $client) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('put')
    <div class="p-4 md:p-5 space-y-4">

        <div class="grid gap-6 mb-6 md:grid-cols-2" x-data="{ name: '{{$client->name}}', random() { return Math.floor(Math.random() * 1000) + 100 } }">
            <div>
                <label for="name"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                <input x-model="name" type="text" id="name" name="name"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="John" required>
            </div>

            <div>
                <label for="police_station"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Police
                    Station</label>
                <input type="text" id="police_station" name="police_station" value="{{$client->police_station}}"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Police Station" required>
            </div>
            <div>
                <label for="username"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                <input x-ref="username" value="{{$client->username}}" type="text" id="username" name="username"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="username" required>
            </div>
            <div>
                <label for="password"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                <input type="password" id="password" name="password"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Password">
            </div>
            <div class="col-span-2 flex justify-center">


                <div class="flex-grow">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                        for="file_input">Upload Photo</label>
                    <input accept=".svg,.png,.jpg" name="avatar"
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                        aria-describedby="file_input_help" id="file_input" type="file">
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">SVG, PNG
                        or JPG (MAX 5MB).</p>
                </div>


            </div>

        </div>


    </div>
    <!-- Modal footer -->
    <div
        class="flex justify-end gap-4 items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
        <x-btn-danger data-modal-hide="edit-modal" type="button">Cancel</x-btn-danger>
        <x-btn-primary data-modal-hide="edit-modal" type="submit">Update</x-btn-primary>
    </div>
</form>