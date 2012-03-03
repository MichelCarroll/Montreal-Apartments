
var NimApart = NimApart || {};

NimApart.postInitialize = function() {
  
  NimApart.map.setOptions({
    
  });
  
  NimApart.addMarkers();
  
};


NimApart.addMarkers = function() {
  
  NimApart.mapMarkers = [];
  
  for(x in NimApart.map_apartments)
  {
    var apt = NimApart.map_apartments[x];
    var marker = new google.maps.Marker({
      position: new google.maps.LatLng(apt.lat, apt.lng),
      title: apt.title
    });
    
    marker.setMap(NimApart.map);
    NimApart.setMarkerActions(marker, apt);
    
    NimApart.mapMarkers.push(marker);
  }
  
};

NimApart.setMarkerActions = function(marker, data) {
  
  var infowindow = new google.maps.InfoWindow({
      content: NimApart.getMarkerContent(data)
  });

  google.maps.event.addListener(marker, 'click', function() {
    window.location = data.link;
  });

  google.maps.event.addListener(marker, 'mouseover', function() {
    infowindow.open(NimApart.map, marker);
  });
  
  google.maps.event.addListener(marker, 'mouseout', function() {
    infowindow.close();
  });
};

NimApart.getMarkerContent = function(data) {
  var contentHtml = "";
  contentHtml += "<h2>"+data.title+"</h2>";
  return contentHtml;
};