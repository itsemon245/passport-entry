<?php 



function getPoliceStations()
{
    $file = file_get_contents(public_path('assets/json/police-stations.json'));
    $json = json_decode($file);
    return $json;    
}


?>