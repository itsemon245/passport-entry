<?php
use NumberFormatter as NF;

function getPoliceStations()
{
    $file = file_get_contents(public_path('assets/json/police-stations.json'));
    $json = json_decode($file);
    return $json;
}

function formatNumber(int | float $value)
{
    $formatter = new NF('en_BD', NF::DEFAULT_STYLE);
    return $formatter->format($value);
}
