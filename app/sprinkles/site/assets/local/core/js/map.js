var markers = [];
var map;
function myMap() {

  //var position = [35.854766, 139.517407];

  var myCenter = new google.maps.LatLng(1.360344, 103.961401);
  var copterPos = new google.maps.LatLng(35.854665, 139.517731);
  var planePos = new google.maps.LatLng(35.854400, 139.517731);
  var roverPos = new google.maps.LatLng(35.854599, 139.517964);
  var drone1Pos = new google.maps.LatLng(35.854766, 139.517407);
  var drone2Pos = new google.maps.LatLng(35.856356, 139.515067);
  var drone3Pos = new google.maps.LatLng(1.379763, 104.051536);
  var sdcar1Pos = new google.maps.LatLng(1.352851, 103.986583);
  var sdcar2Pos = new google.maps.LatLng(1.349722, 103.964051);
  var sdcar3Pos = new google.maps.LatLng(1.399937, 103.856724);
  var sdcar4Pos = new google.maps.LatLng(1.343373, 103.860524);
  
  var mapProp= {
      center:myCenter,
      zoom:13,
      mapTypeId:google.maps.MapTypeId.HYBRID
  };
  map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
  var copterMarker = new google.maps.Marker({
      position: copterPos,
      icon: "ImgRsrc/maps/copter.png",
      id: "copter"
    });
  var planeMarker = new google.maps.Marker({
      position: planePos,
      icon: "ImgRsrc/maps/plane.png",
      id: "plane"
    });
  var roverMarker = new google.maps.Marker({
      position: roverPos,
      icon: "ImgRsrc/maps/rover.png",
      id: "rover"
    });
  var drone1Marker = new google.maps.Marker({
      position: drone1Pos,
      icon: "ImgRsrc/maps/copter.png",
      id: "drone1"
    });
  markers["drone1Marker"] = drone1Marker;
  var drone2Marker = new google.maps.Marker({
      position: drone2Pos,
      icon: "ImgRsrc/maps/copter.png",
      id: "drone2"
    });
  markers["drone2Marker"] = drone2Marker;
  var sdcar1Marker = new google.maps.Marker({
      position: sdcar1Pos,
      icon: "ImgRsrc/maps/selfDrivingCarSmall.png",
      id: "sdcar1"
    });
  markers["sdcar1Marker"] = sdcar1Marker;
  var sdcar2Marker = new google.maps.Marker({
      position: sdcar2Pos,
      icon: "ImgRsrc/maps/selfDrivingCarSmall.png",
      id: "sdcar2"
    });
  markers["sdcar2Marker"] = sdcar2Marker;
  var sdcar3Marker = new google.maps.Marker({
      position: sdcar3Pos,
      icon: "ImgRsrc/maps/selfDrivingCarSmall.png",
      id: "sdcar3"
    });
  markers["sdcar3Marker"] = sdcar3Marker;
   var sdcar4Marker = new google.maps.Marker({
      position: sdcar4Pos,
      icon: "ImgRsrc/maps/selfDrivingCarSmall.png",
      id: "sdcar4"
    });
  markers["sdcar4Marker"] = sdcar4Marker;
  var drone3Marker = new google.maps.Marker({
      position: drone3Pos,
      icon: "ImgRsrc/maps/copter.png",
      id: "drone3"
    });
  markers["drone3Marker"] = drone3Marker;

  copterMarker.setMap(map);
  planeMarker.setMap(map);
  roverMarker.setMap(map);
  drone1Marker.setMap(map);
  drone2Marker.setMap(map);
  drone3Marker.setMap(map);
  sdcar1Marker.setMap(map);
  sdcar2Marker.setMap(map);
  sdcar3Marker.setMap(map);
  sdcar4Marker.setMap(map);

  google.maps.event.addListener(copterMarker,'click',function() {
      //map.setZoom(18);
      map.setCenter(copterMarker.getPosition());
      window.open("hud",'_blank');
  });
  google.maps.event.addListener(planeMarker,'click',function() {
      //map.setZoom(18);
      map.setCenter(planeMarker.getPosition());
      window.open("VR",'_blank');
  });
  google.maps.event.addListener(roverMarker,'click',function() {
      map.setZoom(9);
      map.setCenter(roverMarker.getPosition());
  });
  google.maps.event.addListener(drone1Marker,'click',function() {
      map.setCenter(drone1Marker.getPosition());
      window.open("drone/1",'_blank');
  });
  google.maps.event.addListener(drone2Marker,'click',function() {
      map.setCenter(drone1Marker.getPosition());
      window.open("drone/2",'_blank');
  });
  google.maps.event.addListener(map, 'click', function(event) {
    var result = [event.latLng.lat(), event.latLng.lng()];
    transition(result,planeMarker);
  });
  google.maps.event.addListener(sdcar1Marker,'click',function() {
      window.open("drone/3",'_blank');
  });
  google.maps.event.addListener(sdcar2Marker,'click',function() {
      window.open("drone/4",'_blank');
  });
  google.maps.event.addListener(sdcar3Marker,'click',function() {
      window.open("drone/5",'_blank');
  });
  google.maps.event.addListener(sdcar4Marker,'click',function() {
      window.open("drone/6",'_blank');
  });
  google.maps.event.addListener(drone2Marker,'click',function() {
      window.open("drone/7",'_blank');
  });

  var destinations = [];

  setInterval(drone1Loop,60000);
  setInterval(drone2Loop,60000);
  drone1Loop();
  drone2Loop();
  setInterval(sdcar1Loop,182000);
  setInterval(sdcar2Loop,117000);
  setInterval(sdcar3Loop,390000);
  setInterval(sdcar4Loop,260000);
  sdcar1Loop();
  sdcar2Loop();
  sdcar3Loop();
  sdcar4Loop();

  
  function transition(dest,marker){
    var numDeltas = 1000;
    var delay = 10; //milliseconds
    var i = 0;
    var deltaLat;
    var deltaLng;
    var position = [marker.getPosition().lat(),marker.getPosition().lng()];

    deltaLat = (dest[0] - position[0])/numDeltas;
    deltaLng = (dest[1] - position[1])/numDeltas;
    destinations[marker.id] = dest;
    moveMarker(marker,position,delay,dest);

      function moveMarker(marker,position,delay,dest){
      position[0] += deltaLat;
      position[1] += deltaLng;
      var latlng = new google.maps.LatLng(position[0], position[1]);
      marker.setTitle("Latitude:"+position[0]+" | Longitude:"+position[1]);
      marker.setPosition(latlng);
      if(destinations[marker.id] != dest){
        i = numDeltas;
      }
      if(i!=numDeltas){
          i++;
          setTimeout(moveMarker.bind(null,marker,position,delay,dest), delay);
      }
    }
  }

  
  function drone1Loop(){
    transition([35.853864, 139.518104],drone1Marker);
    setTimeout(transition.bind(null,[35.853991, 139.518462],drone1Marker),10000);
    setTimeout(transition.bind(null,[35.854161, 139.518360],drone1Marker),20000);
    setTimeout(transition.bind(null,[35.854357, 139.518748],drone1Marker),30000);
    setTimeout(transition.bind(null,[35.855135, 139.518147],drone1Marker),40000);
    setTimeout(transition.bind(null,[35.854766, 139.517407],drone1Marker),50000);
  }
  function drone2Loop(){
    transition([35.856740, 139.519426],drone2Marker);
    setTimeout(transition.bind(null,[35.853889, 139.520346],drone2Marker),15000);
    setTimeout(transition.bind(null,[35.853389, 139.515218],drone2Marker),30000);
    setTimeout(transition.bind(null,[35.856356, 139.515067],drone2Marker),45000);
  }

  function sdcar1Loop(){
    transition([1.337066, 103.979644],sdcar1Marker);
    setTimeout(transition.bind(null,[1.325523, 103.972415],sdcar1Marker),13000);
    setTimeout(transition.bind(null,[1.312281, 104.000341],sdcar1Marker),26000);
    setTimeout(transition.bind(null,[1.318532, 104.015726],sdcar1Marker),39000);
    setTimeout(transition.bind(null,[1.357216, 104.031388],sdcar1Marker),52000);
    setTimeout(transition.bind(null,[1.369964, 104.004881],sdcar1Marker),65000);
    setTimeout(transition.bind(null,[1.389118, 103.998102],sdcar1Marker),78000);
    setTimeout(transition.bind(null,[1.386513, 103.979400],sdcar1Marker),91000);
    setTimeout(transition.bind(null,[1.377990, 103.978638],sdcar1Marker),104000);
    setTimeout(transition.bind(null,[1.360149, 103.961147],sdcar1Marker),117000);
    setTimeout(transition.bind(null,[1.350093, 103.965054],sdcar1Marker),130000);
    setTimeout(transition.bind(null,[1.340877, 103.971327],sdcar1Marker),143000);
    setTimeout(transition.bind(null,[1.337048, 103.979895],sdcar1Marker),156000);
    setTimeout(transition.bind(null,[1.352851, 103.986583],sdcar1Marker),169000);

  }
  
  function sdcar2Loop(){
    transition([1.340732, 103.971562],sdcar2Marker);
    setTimeout(transition.bind(null,[1.337020, 103.979939],sdcar2Marker),13000);
    setTimeout(transition.bind(null,[1.360948, 103.989981],sdcar2Marker),26000);
    setTimeout(transition.bind(null,[1.337020, 103.979939],sdcar2Marker),39000);
    setTimeout(transition.bind(null,[1.324591, 103.971687],sdcar2Marker),52000);
    setTimeout(transition.bind(null,[1.328889, 103.964947],sdcar2Marker),65000);
    setTimeout(transition.bind(null,[1.332362, 103.954870],sdcar2Marker),78000);
    setTimeout(transition.bind(null,[1.332362, 103.954870],sdcar2Marker),91000);
    setTimeout(transition.bind(null,[1.338203, 103.960157],sdcar2Marker),104000);
  }

  function sdcar3Loop(){
    transition([1.376667, 103.858621],sdcar3Marker);
    setTimeout(transition.bind(null,[1.369493, 103.861160],sdcar3Marker),13000);
    setTimeout(transition.bind(null,[1.355444, 103.857233],sdcar3Marker),26000);
    setTimeout(transition.bind(null,[1.339004, 103.862018],sdcar3Marker),39000);
    setTimeout(transition.bind(null,[1.330414, 103.862391],sdcar3Marker),52000);
    setTimeout(transition.bind(null,[1.327575, 103.869215],sdcar3Marker),65000);
    setTimeout(transition.bind(null,[1.319998, 103.875539],sdcar3Marker),78000);
    setTimeout(transition.bind(null,[1.315051, 103.874548],sdcar3Marker),91000);
    setTimeout(transition.bind(null,[1.301935, 103.878222],sdcar3Marker),104000);
    setTimeout(transition.bind(null,[1.295456, 103.876550],sdcar3Marker),117000);
    setTimeout(transition.bind(null,[1.295738, 103.893790],sdcar3Marker),130000);
    setTimeout(transition.bind(null,[1.318841, 103.967807],sdcar3Marker),143000);
    setTimeout(transition.bind(null,[1.324604, 103.971846],sdcar3Marker),156000);
    setTimeout(transition.bind(null,[1.327538, 103.979017],sdcar3Marker),169000);
    setTimeout(transition.bind(null,[1.321908, 103.978336],sdcar3Marker),182000);
    setTimeout(transition.bind(null,[1.311761, 104.002800],sdcar3Marker),195000);
    setTimeout(transition.bind(null,[1.317631, 104.014994],sdcar3Marker),208000);
    setTimeout(transition.bind(null,[1.357764, 104.031357],sdcar3Marker),221000);
    setTimeout(transition.bind(null,[1.370093, 104.004649],sdcar3Marker),234000);
    setTimeout(transition.bind(null,[1.388837, 103.998445],sdcar3Marker),247000);
    setTimeout(transition.bind(null,[1.390555, 103.990776],sdcar3Marker),260000);
    setTimeout(transition.bind(null,[1.387638, 103.988673],sdcar3Marker),273000);
    setTimeout(transition.bind(null,[1.386020, 103.978631],sdcar3Marker),286000);
    setTimeout(transition.bind(null,[1.377713, 103.978531],sdcar3Marker),299000);
    setTimeout(transition.bind(null,[1.360384, 103.961337],sdcar3Marker),312000);
    setTimeout(transition.bind(null,[1.364507, 103.956495],sdcar3Marker),325000);
    setTimeout(transition.bind(null,[1.367964, 103.944421],sdcar3Marker),338000);
    setTimeout(transition.bind(null,[1.381758, 103.917200],sdcar3Marker),351000);
    setTimeout(transition.bind(null,[1.399632, 103.897708],sdcar3Marker),364000);
    setTimeout(transition.bind(null,[1.399937, 103.856724],sdcar3Marker),377000);
  }
  function sdcar4Loop(){
    transition([1.344281, 103.865197],sdcar4Marker);
    setTimeout(transition.bind(null,[1.342954, 103.870660],sdcar4Marker),13000);
    setTimeout(transition.bind(null,[1.343462, 103.874303],sdcar4Marker),26000);
    setTimeout(transition.bind(null,[1.344277, 103.876953],sdcar4Marker),39000);
    setTimeout(transition.bind(null,[1.340739, 103.885083],sdcar4Marker),52000);
    setTimeout(transition.bind(null,[1.343262, 103.892043],sdcar4Marker),65000);
    setTimeout(transition.bind(null,[1.337246, 103.909105],sdcar4Marker),78000);
    setTimeout(transition.bind(null,[1.349909, 103.926336],sdcar4Marker),91000);
    setTimeout(transition.bind(null,[1.344693, 103.938938],sdcar4Marker),104000);
    setTimeout(transition.bind(null,[1.352101, 103.954774],sdcar4Marker),117000);
    setTimeout(transition.bind(null,[1.344693, 103.938938],sdcar4Marker),130000);
    setTimeout(transition.bind(null,[1.349909, 103.926336],sdcar4Marker),143000);
    setTimeout(transition.bind(null,[1.337246, 103.909105],sdcar4Marker),156000);
    setTimeout(transition.bind(null,[1.343262, 103.892043],sdcar4Marker),169000);
    setTimeout(transition.bind(null,[1.340739, 103.885083],sdcar4Marker),182000);
    setTimeout(transition.bind(null,[1.344277, 103.876953],sdcar4Marker),195000);
    setTimeout(transition.bind(null,[1.343462, 103.874303],sdcar4Marker),208000);
    setTimeout(transition.bind(null,[1.342954, 103.870660],sdcar4Marker),221000);
    setTimeout(transition.bind(null,[1.344281, 103.865197],sdcar4Marker),234000);
    setTimeout(transition.bind(null,[1.343373, 103.860524],sdcar4Marker),247000);
  }

}
function drone1hover(){
    markers["drone1Marker"].setIcon(highlightedIcon());
}
function drone1out(){
    markers["drone1Marker"].setIcon(normalIcon());
}

function drone2hover(){
      markers["drone2Marker"].setIcon(highlightedIcon());
}
function drone2out(){
    markers["drone2Marker"].setIcon(normalIcon());
}
function sdcar1hover(){
    markers["sdcar1Marker"].setIcon(highlightedIconSDC());
}
function sdcar1out(){
    markers["sdcar1Marker"].setIcon(normalIconSDC());
}

function sdcar2hover(){
      markers["sdcar2Marker"].setIcon(highlightedIconSDC());
}
function sdcar2out(){
    markers["sdcar2Marker"].setIcon(normalIconSDC());
}
function sdcar3hover(){
      markers["sdcar3Marker"].setIcon(highlightedIconSDC());
}
function sdcar3out(){
    markers["sdcar3Marker"].setIcon(normalIconSDC());
}
function sdcar4hover(){
      markers["sdcar4Marker"].setIcon(highlightedIconSDC());
}
function sdcar4out(){
    markers["sdcar4Marker"].setIcon(normalIconSDC());
}

function normalIcon() {
  return {
    url: 'ImgRsrc/maps/copter.png'
  };
}
function highlightedIcon() {
  return {
    url: 'ImgRsrc/maps/copterhover3.png'
  };
}
function normalIconSDC() {
  return {
    url: 'ImgRsrc/maps/selfDrivingCarSmall.png'
  };
}
function highlightedIconSDC() {
  return {
    url: 'ImgRsrc/maps/selfDrivingCarSmallHover.png'
  };
}

function gmapCenterOnMarker(markerName){
  map.setCenter(markers[markerName+"Marker"].getPosition());
}