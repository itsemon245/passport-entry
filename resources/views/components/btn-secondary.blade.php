<button
    {{ $attributes->merge(['class' => 'flex gap-2 items-center justify-between w-max px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue']) }}>
    {{ $slot ?? 'Primary Btn' }}
</button>
