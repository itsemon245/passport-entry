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

    html,
    body {
        height: 100%;
    }

    #wrapper {
        position: fixed;
        overflow: auto;
        left: 0;
        right: 0;
        top: 0;
        bottom: 4px;
        border: 1px solid black;
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


    @page {
        margin: 12px 16px;
        border: 1px solid black;
    }

    .print-only {
        display: none;
    }

    .screen-only {
        display: flex;
        height: 100vh;
        width: 100vw;
        justify-content: center;
        align-items: center;
    }

    @media print {
        .print-only {
            display: block;
        }

        .screen-only {
            display: none;
        }
    }
</style>

<body class="">
    <div class="screen-only">
        <button onclick="window.print()"
            style="background: #7e3af2;border-radius:7px; padding:16px;color:white;font-weight:500;">Print
            Again</button>
    </div>
    <div class="print-only">
        <div id="wrapper"></div>
        <h3 style="text-align: center;font-size:1.4rem; font-weight:bold;margin-bottom:1rem;text-decoration:underline;">
            {{ request()->date_from == request()->date_to ? 'Daily File Statement' : 'Report Statement' }}
            <div style="color:#7e3af2;font-weight:500;font-size: 1.1rem;margin-top:13px;">
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
                    @php
                        $condition = true;
                        $i = 0;
                    @endphp
                    @if ($client->rowspan == 0)
                        @php
                            $sl = $count + 1;
                        @endphp
                        <tr>
                            <td>{{ $sl }}.</td>
                            <td class="text-left">
                                <p class="no-padding no-margin">{{ $client->name }}</p>
                            </td>
                            <td>
                                {{ $client->channel_count }}
                            </td>
                            <td>
                                {{ $client->general_count }}
                            </td>
                            <td class="no-padding">
                                -
                            </td>
                            <td class="no-padding">
                                -
                            </td>

                            <td class="no-padding">IO
                            </td>
                        </tr>
                    @else
                        @foreach ($client->entries as $i => $entry)
                            @php
                                $sl = $count + $i + 1;
                            @endphp
                            <tr>
                                <td>{{ $sl }}.</td>
                                @if ($i == 0)
                                    <td rowspan="{{ $client->rowspan }}" class="text-left">
                                        <p class="no-padding no-margin">{{ $client->name }}</p>
                                    </td>
                                    <td rowspan="{{ $client->rowspan }}">
                                        {{ $client->channel_count }}
                                    </td>
                                    <td rowspan="{{ $client->rowspan }}">
                                        {{ $client->general_count }}
                                    </td>
                                @endif
                                <td class="no-padding">
                                    <span>
                                        {{ $entry->application_id ?? '-' }}
                                    </span>
                                    @if ($entry->remarks)
                                        <span class="italic">({{ $entry->remarks }})</span>
                                    @endif
                                </td>
                                <td class="no-padding">
                                    {!! $entry->police_station ?? '<span style="font-weight: 500; font-size: 1.2rem;">-</span>' !!}
                                </td>
                                @if ($i == 0)
                                    <td class="no-padding" rowspan="{{ $client->rowspan }}">
                                        IO
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @endif
                    @php
                        $count = $key == 0 ? ($client->rowspan > 0 ? $client->rowspan : 1) : $sl;
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
    </div>


    <script>
        window.addEventListener('load', function() {
            window.print()
        })
    </script>
</body>

</html>
