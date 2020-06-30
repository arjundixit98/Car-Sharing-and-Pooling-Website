var myLatLng = {lat: 51.5, lng: -0.1};
var mapOptions = {
    center: myLatLng,
    zoom: 10,
    mapTypeId: google.maps.MapTypeId.ROADMAP

};


var input1 = document.getElementById("departure");
var input2 = document.getElementById("destination");

var options = {
    types: ['(cities)']   
}
var autocomplete1 = new google.maps.places.Autocomplete(input1, options);
var autocomplete2 = new google.maps.places.Autocomplete(input2, options);
//var autocomplete3 = new google.maps.places.Autocomplete(input3, options);
//var autocomplete4 = new google.maps.places.Autocomplete(input4, options);


var directionsService = new google.maps.DirectionsService();


google.maps.event.addDomListener(window, 'load', initialize);


function initialize() {
    
    directionsDisplay = new google.maps.DirectionsRenderer();
    
    map=new google.maps.Map(document.getElementById("googleMap"),mapOptions);
    
    directionsDisplay.setMap(map);
}


google.maps.event.addListener(autocomplete1, 'place_changed', calcRoute);
google.maps.event.addListener(autocomplete2, 'place_changed', calcRoute);


function calcRoute() {
    var start = $('#departure').val();
    var end = $('#destination').val();
    var request = {
        origin:start, 
        destination:end,
        travelMode: google.maps.DirectionsTravelMode.DRIVING,
        unitSystem: google.maps.UnitSystem.IMPERIAL,
        durationInTraffic: false,   
        avoidHighways: false,   
        avoidTolls: false,
    };
    if(start && end){
        directionsService.route(request, function(response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(response);
            }else{ 
                initialize();
            }
        });
    }
}