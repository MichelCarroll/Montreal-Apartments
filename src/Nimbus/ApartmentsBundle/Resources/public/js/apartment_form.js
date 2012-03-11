
var NimApart = NimApart || {};

NimApart.geocodeAddr = 'http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=';

NimApart.postInitialize = function() {
  
  NimApart.map.setOptions({
    scrollwheel: false, 
    draggable: false, 
    panControl: false, 
    rotateControl: false, 
    scaleControl: false,
    streetViewControl: false,
    mapTypeControl: false
  });
  
};

NimApart.pinpointMapAddressFromForm = function() {
  
  var address_string = $('#apartment_address').val();
  
  var geocoder = new google.maps.Geocoder();
  var request = {
    'address': address_string,
    'bounds': new google.maps.LatLngBounds(
      new google.maps.LatLng(NimApart.globals.bounds.sw.lat, NimApart.globals.bounds.sw.lng),
      new google.maps.LatLng(NimApart.globals.bounds.ne.lat, NimApart.globals.bounds.ne.lng))
  };
  
  geocoder.geocode(request, function(result) {
    if(result.length)
    {
      var resultType = result[0].geometry.location_type;
      if(resultType != google.maps.GeocoderLocationType.GEOMETRIC_CENTER)
      {
        var point = result[0].geometry.location;
        if(point && NimApart.pointInsideBounds(point))
        {
          NimApart.refreshFormMarker(point);
          
          $("#apartment_map").attr('class', '');
          NimApart.map.setOptions({
            draggable: true
          });
        }
      }
    }
  });
  
};

NimApart.pointInsideBounds = function(point) {
  var swLat = NimApart.globals.bounds.sw.lat;
  var neLat = NimApart.globals.bounds.ne.lat;
  var swLng = NimApart.globals.bounds.sw.lng;
  var neLng = NimApart.globals.bounds.ne.lng;
  
  if(point.lat() < swLat || point.lat() > neLat)
  {
    return false;
  }
  
  if(point.lng() < swLng || point.lng() > neLng)
  {
    return false;
  }
  
  return true;
};


NimApart.refreshFormMarker = function(newPoint) {
      
    if(NimApart.formMarker) {
      NimApart.formMarker.setMap(null);
    }  
      
    NimApart.formMarker = new google.maps.Marker({
        position: newPoint
    });
  
    NimApart.formMarker.setMap(NimApart.map);
    
    NimApart.map.panTo(newPoint);
    NimApart.map.setZoom(NimApart.globals.spotlightZoom);
    
    $('#apartment_latitude').val(newPoint.lat());
    $('#apartment_longitude').val(newPoint.lng());
    
};

$(document).ready(function() {
  
  $('#apartment_address').keyup(function() {
    
    clearTimeout(NimApart.refreshAddressTimeout);
    
    NimApart.refreshAddressTimeout = 
      setTimeout("NimApart.pinpointMapAddressFromForm();", NimApart.globals.refreshRate);
    
  });
  
});