<html>
<head>
<?php
    $lat = Input::has('latitude') ? Input::get('latitude') : '38.657633';
    $lon = Input::has('longitude') ? Input::get('longitude') : '36.936035';
    $zoom= Input::has('zoom') ? Input::get('zoom') : 6;
?>

<!--Map Process start-->
<style>
#map-canvas {
    height: 100%;
    margin: 0px;
    padding: 0px
}
</style>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script>        
function gLoad() {
    var latit = '{{ $lat }}';
    var longtit = '{{ $lon }}';
    var zoom_map = '{{ $zoom }}';
    var lat = parseFloat(latit);  //convert string to float
    var lon = parseFloat(longtit);
    var zooming = parseFloat(zoom_map);
    var myLatLng = new google.maps.LatLng(lat,lon);
    var mapOptions = {
        scrollwheel: false,
        center: myLatLng,
        zoom: zooming,
        panControl: false, zoomControl: true, zoomControlOptions: { style: google.maps.ZoomControlStyle.SMALL },
        overviewMapControl: false,
        scaleControl: false,
        mapTypeControl: false,
        streetViewControl: false,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    var map = new google.maps.Map(document.getElementById("map"), mapOptions);

    var point = new google.maps.Marker({position: myLatLng, map: map});
}
</script>
</head>
<body onload="gLoad();" style="margin: 0; padding: 0;">
    <div id="mapContainer">
        <div id="map" style="height: 250px; max-width: 100%; "> </div>
    </div>
</body>
<!--Map Process End-->
</html>