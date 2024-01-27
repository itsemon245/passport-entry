<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Report PDF</title>
    <style>
        {!! Vite::content('resources/css/app.css') !!}
    </style>
</head>

<style>
    :root {
        font-size: 14px;
    }

    *,
    body,
    table,
    tr,
    td {
        box-sizing: border-box;
    }

    table {
        width: 100%;
        /* border-collapse: collapse; */
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

    s .first-child,
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

    table {
        page-break-inside: auto;
    }

    tr {
        page-break-inside: auto;
        page-break-after: auto;
    }

    .page-break-before {
        border-bottom: 1px solid #000;
    }

    thead {
        display: table-header-group;
    }

    tfoot {
        display: table-footer-group;
    }

    @page {
        margin: 12px 16px;
        border: 1px solid black;
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
        <tbody>
            <tr>
                <th rowspan="2">No.</th>
                <th rowspan="2" class="text-center">IO Name</th>
                <th colspan="2">Statement</th>
                <th rowspan="2">Enrollment ID</th>
                <th rowspan="2">P.S Name</th>
                <th rowspan="2">Agency</th>
            </tr>
            <tr>
                <th>Channel</th>
                <th>General</th>
            </tr>
            @php
                $count = 0;
            @endphp
            @forelse ($clients as $key => $client)
                @foreach ($client->entries as $index => $entry)
                    @php
                        $sl = $count + $index + 1;
                    @endphp
                    <tr>
                        <td>{{ $sl }}.</td>
                        @if ($index == 0)
                            <td rowspan="{{ $client->entries->count() }}" class="text-left">
                                <p class="no-padding no-margin">{{ $client->name }}</p>
                            </td>
                            <td rowspan="{{ $client->entries->count() }}">
                                {{ $client->channel_count }}
                            </td>
                            <td rowspan="{{ $client->entries->count() }}">
                                {{ $client->general_count }}
                            </td>
                        @endif
                        <td class="no-padding">
                            {{ $entry->application_id ?? '-' }}
                        </td>
                        <td class="no-padding">
                            {!! $entry->police_station ?? '<span style="font-weight: 500; font-size: 1.2rem;">-</span>' !!}
                        </td>
                        @if ($index == 0)
                            <td class="no-padding" rowspan="{{ $client->entries->count() }}">IO</td>
                        @endif
                    </tr>
                @endforeach
                @php
                    $count = $key == 0 ? $client->entries->count() : $sl;
                @endphp
            @empty
                <x-tr.no-records colspan="" />
            @endforelse
            <tr>
                <td></td>
                <td class="text-right px-2 font-bold">
                    Total
                </td>
                <td class="font-bold">
                    {{ $clients->sum('channel_count') }}
                </td>
                <td class="font-bold">
                    {{ $clients->sum('general_count') }}
                </td>
                <td class="no-padding">

                </td>
                <td class="no-padding">

                </td>
                <td class="no-padding"></td>
            </tr>
        </tbody>
    </table>
</body>

</html>
