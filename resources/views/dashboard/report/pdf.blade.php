<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Report PDF</title>
</head>

<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    td,
    th {
        border: 1px solid black;
        text-align: center;
        vertical-align: middle;
    }

    td,
    th {
        padding: 2px;
    }

    .first-child,
    .border-none {
        border: none;
    }

    .first-child {
        border-top: 1px solid black;
    }

    .not-center {
        text-align: start;
    }

    .not-padding {
        padding: 0;
    }
</style>

<body>
    <h3 style="text-align: center;font-size:20px; font-weight:bold;margin-bottom:8px;">Report List (<span
            style="color:#7e3af2;">
            {{ \Carbon\Carbon::parse(request()->date_from)->format('d F, Y') }} -
            {{ \Carbon\Carbon::parse(request()->date_to)->format('d F, Y') }}</span>) </h3>
    <table>
        <thead>
            <tr>
                <th rowspan="2">No.</th>
                <th rowspan="2">Client Name</th>
                <th colspan="2">Survey</th>
                <th rowspan="2">Application ID</th>
                <th rowspan="2">Police Station</th>
            </tr>
            <tr>
                <th>Channel</th>
                <th>General</th>
            </tr>
        </thead>
        <tbody class="">
            @forelse ($clients as $key => $client)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td class="not-center">
                        <p class="font-semibold">{{ $client->name }}</p>
                    </td>
                    <td>
                        {{ $client->channel_count }}
                    </td>
                    <td>
                        {{ $client->general_count }}
                    </td>
                    <td class="no-padding">
                        @foreach ($client->entries as $i => $entry)
                            <table style="width:100%;border-collapse:collapse;"
                                class="{{ $client->entries->count() == 1 || $i == 0 ? '' : 'first-child' }} w-full">
                                <tr class="border-none">
                                    <td class="border-none">{{ $entry->application_id }}</td>
                                </tr>
                            </table>
                        @endforeach
                    </td>
                    <td class="no-padding">
                        @foreach ($client->entries as $i => $entry)
                            <table style="width:100%;border-collapse:collapse;"
                                class="{{ $client->entries->count() == 1 || $i == 0 ? '' : 'first-child' }} w-full">
                                <tr class="border-none">
                                    <td class="border-none">{{ $entry->police_station }}</td>
                                </tr>
                            </table>
                        @endforeach
                    </td>
                </tr>
            @empty
                <x-tr.no-records colspan="7" />
            @endforelse
        </tbody>
    </table>
</body>

</html>
