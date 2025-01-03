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
            {{ request()->date_from == request()->date_to ? 'Daily File Statement(Thanawise)' : 'Report Statement(Thanawise)' }}
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
                    <th>No.</th>
                    <th colspan="2" class="text-center">IO Name</th>
                    <th>Channel</th>
                    <th>General</th>
                    <th>Total</th>
                </tr>
                @php
                    $count = 0;
                    $totalChannel = 0;
                    $totalGeneral = 0;
                    $total = 0;
                @endphp
                @forelse ($thanas as $thana => $clients)
                    @php
                        $count++;
                    @endphp
                    @foreach ($clients as $name => $client)
                        @php
                            $totalChannel += $client->channel_count;
                            $totalGeneral += $client->general_count;
                            $total += $client->channel_count + $client->general_count;
                        @endphp
                        <tr>
                            @if ($loop->first)
                                <td rowspan="{{ $clients->count() }}">{{ $count }}</td>
                                <td rowspan="{{ $clients->count() }}" class="text-center">{{ $thana }}</td>
                            @endif
                            <td class="text-left">
                                <p class="no-padding no-margin">{{ $name }}</p>
                            </td>
                            <td>
                                {{ $client->channel_count }}
                                @if ($client->negative_count > 0)
                                    <span class="italic">(negative = {{ $client->negative_count }})</span>
                                @endif
                                @if ($client->second_time_count > 0)
                                    <span class="italic">(second_time = {{ $client->second_time_count }})</span>
                                @endif
                            </td>
                            <td>
                                {{ $client->general_count }}
                            </td>
                            <td>{{ $client->channel_count + $client->general_count }}</td>
                        </tr>
                    @endforeach
                @empty
                    <x-tr.no-records colspan="" />
                @endforelse
                <tr>
                    <td colspan="3" class="text-right px-2 font-bold">
                        Total
                    </td>
                    <td class="font-bold">
                        {{ $totalChannel }}
                    </td>
                    <td class="font-bold">
                        {{ $totalGeneral }}
                    </td>
                    <td class="no-padding font-bold">
                        {{ $total }}
                    </td>
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
