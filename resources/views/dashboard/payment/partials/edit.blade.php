<tr class="text-gray-700 dark:text-gray-400">
    <td class="align-middle text-center">{{ ++$key }}</td>
    <td class="px-4 text-sm py-3">
        <div>
            <div class="relative flex items-center">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z">
                        </path>
                    </svg>
                </div>
                <input id="created_at" name="created_at" type="date"
                    value="{{ $payment->created_at->format('Y-m-d') }}"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Select payment date">
            </div>
        </div>
    </td>
    <td class="px-4 text-sm py-3">
        <div class="flex items-center gap-3">
            <input id="amount" name="amount" type="number" value="{{ $payment->amount }}"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Payment Amount">
            <x-heroicon-o-currency-bangladeshi class="w-5 h-5 inline mb-1"></x-heroicon-o-currency-bangladeshi>
        </div>
    </td>
    <td class="px-4 text-sm py-3 capitalize">
        <div>
            <select name="payment_method"
                class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                <option value="cash" @selected($payment->payment_method == 'cash')>Cash</option>
                <option value="bkash" @selected($payment->payment_method == 'bkash')>Bkash</option>
                <option value="discount" @selected($payment->payment_method == 'discount')>Discount</option>
            </select>
        </div>
    </td>
    <td class="px-4 py-3">
        <button hx-get="{{ route('payment.delete', $payment) }}"
            class="w-max flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
            aria-label="Update">
            Update
        </button>
    </td>

</tr>
