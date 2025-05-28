<?php

require(dirname(__FILE__) . '/wp-blog-header.php');

$calendar_id = '6fdebd1f6236b6e4a5780a1ac33d361a7c49b1fd0a2124c8a3ba4ad2cecd934f@group.calendar.google.com';
$api_key = 'AIzaSyDi873ogok_bPvYEhYhJhO7a6iMEZp-2jM';
$url = "https://www.googleapis.com/calendar/v3/calendars/{$calendar_id}/events?key={$api_key}";

$url = 'https://calendar.google.com/calendar/u/1?cid=ZGNsbW4uY29tbXNAZ21haWwuY29t';

//pobj(napco_curl('https://calendar.google.com/calendar/ical/qderttmf79765mestikfcf0mro%40group.calendar.google.com/private-71a4c9763dee03c2a2c7f7219fda82a4/basic.ics'));

$result = file_get_contents($url);
pobj($result);
// exit;