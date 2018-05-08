var cameraDatas= [
					["-28.3 9.73 28.91","-22.2 81.87 0"],//LAB
					["-37.2 13.3 -12.1","-40.6 34.2 0"],//Title
					["20 15 -6.60","0 0 0"],//Cloud
					[["67.98 26.81 25.98","-25.7 58.49 0"],["40.83 5.2 4.5","-6.9 9 0"]],//to break
					["32.85 6.27 -30.1","29.47 0 0"],//to back
					["15.667 92.332 -7.681","-90.000 12.084 0"],
					["800 1000 600","-90.000 0 0"],
					["0.134 9.523 -4.111","-25.955 129.546 0"]
				];
var moveList = [];
var loopTime = 10000; //milliseconds
var startTime = 10000; //milliseconds

var simEvent = new Event('keydown');
simEvent.keyCode = 78;

$(document).ready(function(){
	var slideMin = 0;
	var slideCounter = 0;
	var slideMax = cameraDatas.length-1;
	autoSlideInit();
	document.addEventListener('keydown', function(event) {
		if(event.keyCode == 37) {
	        console.log('Left was pressed');
	    }
	    else if(event.keyCode == 39) {
	        console.log('Right was pressed');
	    }
	    else if(event.keyCode == 13 || event.keyCode == 78) {
	        console.log('Next was pressed');
	        slideCounter++;//Next slide
	        if(slideCounter>slideMax){
				slideCounter = slideMin;
			}
	        goToSlide(slideCounter);
	    }
	    else if(event.keyCode == 8 || event.keyCode == 80) {
	    	console.log('Previous was pressed');
	    	slideCounter--;//Previous slide
	        if(slideCounter < slideMin){
				slideCounter = slideMax;
			}
	        goToSlide(slideCounter);
	    }
	});
	document.querySelector('#camera').addEventListener('animation__p-complete', function (evt) {
		finishMoveList();
		//if (evt.detail.name === 'position') {
	    //	console.log('Entity has moved from', evt.detail.oldData, 'to', evt.detail.newData, '!');
	  	//}
	});
});
function goToSlide(slideNum){
	moveList = getSlideCamData(slideNum);
	finishMoveList();
	return;
}
function goToData(data){
	var cam = document.querySelector('#camera');
	var rota = cam.getAttribute('rotation');
	var pos = cam.getAttribute('position');
	console.log(pos);
	console.log(rota);
	var destPos = data[0].split(" ");
	var destRota = data[1].split(" ");
	cam.setAttribute('animation__p','from',pos['x']+" "+pos['y']+" "+pos['z']);
	cam.setAttribute('animation__r','from',rota['x']+" "+rota['y']+" "+rota['z']);
	cam.setAttribute('animation__p','to',destPos[0]+" "+destPos[1]+" "+destPos[2]);
    cam.setAttribute('animation__r','to',destRota[0]+" "+destRota[1]+" "+destRota[2]);
	document.querySelector('#camera').emit('startAnim');
}
function finishMoveList(){
	if(typeof(moveList[0]) == "string"){
		goToData(moveList);
		moveList= [];
	}else
	if (Array.isArray(moveList[0])){
		goToData(moveList[0]);
		moveList.shift();
	}
}
function getSlideCamData(slideNum){
	var camDataCopy = JSON.parse(JSON.stringify(cameraDatas));
	return camDataCopy[slideNum];
}
function autoSlideInit(){
	setTimeout(function(){ 
		console.log('start slideshow'); 
		autoSlideStartLoop();
	}, startTime);
	
}
function autoSlideStartLoop(){
	setInterval(function(){
		document.dispatchEvent(simEvent);
	}, loopTime);
}
