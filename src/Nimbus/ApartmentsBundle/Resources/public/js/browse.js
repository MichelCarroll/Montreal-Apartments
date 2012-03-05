
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
      //title: apt.title
    });
    
    marker.setMap(NimApart.map);
    NimApart.setMarkerActions(marker, apt);
    
    NimApart.mapMarkers.push(marker);
  }
  
};

NimApart.setMarkerActions = function(marker, data) {
  
  var infowindow = new google.maps.InfoWindow({
      content: NimApart.apartDataToBubbleContent(data)
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

NimApart.apartDataToBubbleContent = function(data) {
  var contentHtml = 
    '<div class="thumbnail">\n\ ';
  
  if(data.image_source)
  {
    contentHtml += '<img src="'+data.image_source+'" />\n\ ';
  }
  
  contentHtml += '<div class="caption">\n\
    <h5>'+data.title+'</h5>\n\
    <p>'+data.description+'</p>\n\
    </div></div>';
  return contentHtml;
};