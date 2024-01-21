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
    :root {
        font-size: 14px;
    }

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

    .no-padding {
        padding: 0;
    }

    .no-margin {
        margin: 0;
        padding: 1px;
    }
</style>

<body>
    <h3 style="text-align: center;font-size:1.4rem; font-weight:bold;margin-bottom:1.5rem;text-decoration:underline;">
        {{ request()->date_from == request()->date_to ? 'Daily File Statement' : 'Report Statement' }}
        <div style="color:#7e3af2;font-weight:500;font-size: 1.1rem;margin-top:1rem;">
            <span>Date:</span>
            (
            @if (request()->date_from == request()->date_to)
                {{ \Carbon\Carbon::parse(request()->date_from)->format('d F, Y') }}
            @else
                {{ \Carbon\Carbon::parse(request()->date_from)->format('d F, Y') }} -
                {{ \Carbon\Carbon::parse(request()->date_to)->format('d F, Y') }}
            @endif
            )
        </div>
    </h3>
    <table>
        <thead>
            <tr>
                <th rowspan="2">No.</th>
                <th rowspan="2">Client Name</th>
                <th colspan="2">Survey</th>
                <th rowspan="2">Enrollment ID</th>
                <th rowspan="2">P.S Name</th>
                <th rowspan="2">Agency</th>
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
                        <p class="no-padding no-margin">{{ $client->name }}</p>
                    </td>
                    <td>
                        {{ $client->channel_count }}
                    </td>
                    <td>
                        {{ $client->general_count }}
                    </td>
                    <td class="no-padding">
                        @if ($client->entries->count() > 0)
                            @foreach ($client->entries as $i => $entry)
                                <table style="width:100%;border-collapse:collapse;"
                                    class="{{ $client->entries->count() == 1 || $i == 0 ? '' : 'first-child' }} w-full">
                                    <tr class="border-none">
                                        <td class="border-none">{{ $entry->application_id }}</td>
                                    </tr>
                                </table>
                            @endforeach
                        @else
                            <span style="font-weight: 500; font-size: 1.2rem;">-</span>
                        @endif
                    </td>
                    <td class="no-padding">
                        @if ($client->entries->count() > 0)
                            @foreach ($client->entries as $i => $entry)
                                <table style="width:100%;border-collapse:collapse;"
                                    class="{{ $client->entries->count() == 1 || $i == 0 ? '' : 'first-child' }} w-full">
                                    <tr class="border-none">
                                        <td class="border-none">{!! $entry->police_station ?? '<span style="font-weight: 500; font-size: 1.2rem;">-</span>' !!}</td>
                                    </tr>
                                </table>
                            @endforeach
                        @else
                            <span style="font-weight: 500; font-size: 1.2rem;">-</span>
                        @endif
                    </td>
                    <td class="no-padding">IO</td>
                </tr>
            @empty
                <x-tr.no-records colspan="" />
            @endforelse
        </tbody>
    </table>
</body>

</html>
