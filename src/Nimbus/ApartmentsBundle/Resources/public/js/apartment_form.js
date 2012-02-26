
var NimApart = NimApart || {};

NimApart.geocodeAddr = 'http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=';

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
        NimApart.refreshFormMarker(point);
      }
    }
  });
  
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
    NimApart.map.setZoom(14);
    
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