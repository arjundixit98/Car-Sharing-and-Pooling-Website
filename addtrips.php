<?php

session_start();
include('connection.php');
$errors = "";

$monday = "";
$tuesday = "";
$wednesday = "";
$thursday = "";
$friday = "";
$saturday = "";
$sunday = "";

$missingdeparture = '<p><strong>Please enter your departure!</strong></p>';
$invaliddeparture = '<p><strong>Please enter a valid departure!</strong></p>';
$missingdestination = '<p><strong>Please enter your destination!</strong></p>';
$invaliddestination = '<p><strong>Please enter a valid destination!</strong></p>';
$missingprice = '<p><strong>Please choose a price per seat!</strong></p>';
$invalidprice = '<p><strong>Please choose a valid price per seat using numbers only!!</strong></p>';
$missingseatsavailable = '<p><strong>Please select the number of available seats!</strong></p>';
$invalidseatsavailable = '<p><strong>The number of available seats should contain digits only!</strong></p>';
$missingfrequency = '<p><strong>Please select a frequency!</strong></p>';
$missingdays = '<p><strong>Please select at least one weekday!</strong></p>';
$missingdate = '<p><strong>Please choose a date for your trip!</strong></p>';
$missingtime = '<p><strong>Please choose a time for your trip!</strong></p>';


$departure = $_POST["departure"];
$destination = $_POST["destination"];
//  $price = $_POST["price"];
$seatsavailable = $_POST["seatsavailable"];
$regular = $_POST["regular"];
$date = $_POST["date"];
$time = $_POST["time"];
if (isset($_POST['monday'])) {
$monday = $_POST["monday"];
}
if (isset($_POST['tuesday'])) {
$tuesday = $_POST["tuesday"];
}
if (isset($_POST['wednesday'])) {
$wednesday = $_POST["wednesday"];
}
if (isset($_POST['thursday'])) {
$thursday = $_POST["thursday"];
}
if (isset($_POST['friday'])) {
$friday = $_POST["friday"];
}
if (isset($_POST['saturday'])) {
$saturday = $_POST["saturday"];
}
if (isset($_POST['sunday'])) {
$sunday = $_POST["sunday"];
}

$distance = getDistance($departure, $destination, "K");
function getDistance($addressFrom, $addressTo, $unit = ''){
    // Google API key
    $apiKey = 'AIzaSyDggofUxTHuVIyd7vsZooPN7JLaA-MvY7E';
    
    
    $formattedAddrFrom    = str_replace(' ', '+', $addressFrom);
    $formattedAddrTo     = str_replace(' ', '+', $addressTo);
    
    
    $geocodeFrom = file_get_contents('https:apiKey);
    $outputFrom = json_decode($geocodeFrom);
    if(!empty($outputFrom->error_message)){
        return $outputFrom->error_message;
    }
    
    
    $geocodeTo = file_get_contents('https:apiKey);
    $outputTo = json_decode($geocodeTo);
    if(!empty($outputTo->error_message)){
        return $outputTo->error_message;
    }
    
    
    $latitudeFrom    = $outputFrom->results[0]->geometry->location->lat;
    $longitudeFrom    = $outputFrom->results[0]->geometry->location->lng;
    $latitudeTo        = $outputTo->results[0]->geometry->location->lat;
    $longitudeTo    = $outputTo->results[0]->geometry->location->lng;
    
    
    $theta    = $longitudeFrom - $longitudeTo;
    $dist    = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
    $dist    = acos($dist);
    $dist    = rad2deg($dist);
    $miles    = $dist * 60 * 1.1515;
    
    
    $unit = strtoupper($unit);
    if($unit == "K"){
        return round($miles * 1.609344, 2).' km';
    }elseif($unit == "M"){
        return round($miles * 1609.344, 2).' meters';
    }else{
        return round($miles, 2).' miles';
    }
}

$price = $distance * 7;

if(!isset($_POST["departureLatitude"]) or !isset($_POST["departureLongitude"])){
    $errors .= $invaliddeparture;   
}else{
    $departureLatitude = $_POST["departureLatitude"];
    $departureLongitude = $_POST["departureLongitude"];
}

if(!isset($_POST["destinationLatitude"]) or !isset($_POST["destinationLongitude"])){
    $errors .= $invaliddestination;   
}else{
    $destinationLatitude = $_POST["destinationLatitude"];
    $destinationLongitude = $_POST["destinationLongitude"];
}



if(!$departure){
    $errors .= $missingdeparture;   
}else{
    $departure = filter_var($departure, FILTER_SANITIZE_STRING); 
}


if(!$destination){
    $errors .= $missingdestination;   
}else{
    $destination = filter_var($destination, FILTER_SANITIZE_STRING); 
}


/*if(!$price){
    $errors .= $missingprice; 
}elseif(preg_match('/\D/', $price)  
){
        $errors .= $invalidprice;   
}else{
    $price = filter_var($price, FILTER_SANITIZE_STRING);    
}*/


if(!$seatsavailable){
    $errors .= $missingseatsavailable; 
}elseif(preg_match('/\D/', $seatsavailable)  
){
        $errors .= $invalidseatsavailable;   
}else{
    $seatsavailable = filter_var($seatsavailable, FILTER_SANITIZE_STRING);    
}


/*if(!$regular){
    $errors .= $missingfrequency;    
}else*/if($regular == "Y"){
    if(!$monday && !$tuesday && !$wednesday && !$thursday && !$friday && !$saturday && !$sunday ){
        $errors .= $missingdays; 
    }
    if(!$time){
        $errors .= $missingtime;   
    }
}elseif($regular == "N"){
    if(!$date){
        $errors.= $missingdate;   
    }
    if(!$time){
        $errors .= $missingtime;   
    }
}


if($errors){
    $resultMessage = "<div class='alert alert-danger'>$errors</div>";
    echo $resultMessage;
}else{
    
    $tbl_name = 'carsharetrips';
    $departure = mysqli_real_escape_string($link, $departure);
    $destination = mysqli_real_escape_string($link, $destination);
    if($regular == "Y"){
        
        $sql = "INSERT INTO $tbl_name (`user_id`,`departure`, `departureLongitude`, `departureLatitude`, `destination`, `destinationLongitude`, `destinationLatitude`, `price`, `seatsavailable`, `regular`, `monday`, `tuesday`, `wednesday`, `thursday`, `friday`, `saturday`, `sunday`, `time`) VALUES ('".$_SESSION['user_id']."', '$departure','$departureLongitude','$departureLatitude','$destination','$destinationLongitude','$destinationLatitude','$price','$seatsavailable','$regular','$monday','$tuesday','$wednesday','$thursday','$friday','$saturday','$sunday','$time')";
    }else{ 
        
        $sql = "INSERT INTO $tbl_name (`user_id`,`departure`, `departureLongitude`, `departureLatitude`, `destination`, `destinationLongitude`, `destinationLatitude`, `price`, `seatsavailable`, `regular`, `date`, `time`) VALUES ('".$_SESSION['user_id']."', '$departure','$departureLongitude','$departureLatitude','$destination','$destinationLongitude','$destinationLatitude','$price','$seatsavailable','$regular','$date','$time')";   
    }
    $results = mysqli_query($link, $sql);
    
    if(!$results){
        echo '<div class=" alert alert-danger">There was an error! The trip could not be added to database!</div>';        
    }
}



?>
