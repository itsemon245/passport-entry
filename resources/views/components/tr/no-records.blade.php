<tr
    class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
    <td colspan="{{ $attributes->get('colspan') }}" class="text-center p-4 bg-slate-200">
        {{ empty($slot->contents) ? 'No records found' : $slot }}
    </td>
</tr>
