<?php

// Create a new DateTime object
$currentDateTime = new \DateTime();

// Format the date and time as per your requirements
$formattedDateTime = $currentDateTime->format('Y-m-d H:i:s');

// Display the formatted date and time
// echo $formattedDateTime;


function timeZoneConvert($fromTime, $fromTimezone, $toTimezone='',$format = 'Y-m-d H:i:s') {
    if($toTimezone == '') $toTimezone = date_default_timezone_get();
     // create timeZone object , with fromtimeZone
    $from = new \DateTimeZone($fromTimezone);
     // create timeZone object , with totimeZone
    $to = new \DateTimeZone($toTimezone);
    // read give time into ,fromtimeZone
    $orgTime = new \DateTime($fromTime, $from);
    // fromte input date to ISO 8601 date (added in PHP 5). the create new date time object
    $toTime = new \DateTime($orgTime->format("c"));
    
    // set target time zone to $toTme ojbect.
    $toTime->setTimezone($to);
    // return reuslt.
    return $toTime->format($format);
}




?>
