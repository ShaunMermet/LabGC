var state = {};
state.roll = 0;
state.pitch = 0;
state.yaw = 0;
state.y = 0;
$(document).ready(function(){
	console.log("hud.js loaded");
  generateRollValue();
  generatePitchValue();
  //setPitch(90);
  //setRoll(-75);
});



function setRoll(angle){
	
	state.roll = angle;
	updateImage();
}
function getYCenterRot (){//return value [0,100] % of the image where the center of rota is. 0 is top, 100 is bottom
	var source = state.pitch;
	//var res = -10/7*source+50;	// fct for top image is 35 degree
	//var res = -source+50;	// fct for top image is 50 degree
	var res = -55.74/180*source+50;	// fct for top image is (90 degree + visual)    //depending image height in pixel and max pitch in pixel 
	return res;
}
function setPitch(angle){
	

	state.pitch = angle;
	updateImage();
	
}
function getYPitchImg (){ //return a value [-50, 50] for the posy of the image, 50 is top, -50 is bottom
	var angle = state.pitch;
	var res = 10/7*angle; 	// temp fct, top image is 35 degree
	//var res = angle; 	// temp fct, top image is 50 degree
							// fct  , top image is ( 90 degree + some visual space)
	return res;
}
function updateImage(){
	imgY = getYPitchImg();
	var rotCenterY = getYCenterRot();//0-100

	//layer background
	var pitchdiv = document.getElementById("imageDiv");
	pitchdiv.style.top = imgY+'%';

	$("#image").rotate({
	    angle: state.roll,
	    center: ["50%", rotCenterY+'%']
	    });
	
	//layer pitch scale
	var pitchdiv = document.getElementById("pitchDiv");
	pitchdiv.style.top = imgY+'%';

	$("#pitchImage").rotate({
	    angle: state.roll,
	    center: ["50%", rotCenterY+'%']
	    });
	
	//layer roll
	$("#rollImage").rotate(state.roll);


	//////////// TEST IMAGE //////////////
	//var pitchimg = document.getElementById("testimage");
	//pitchimg.style.top = imgY+'%';
	//$("#testimage").rotate({
	    //angle: state.roll,
	    //center: ["50%", rotCenterY+'%']
	    //});
}

function generateRollValue(){
	var rollAngle = 0;
	var clockwise = true;
	setInterval(function(){
    if(clockwise == true){
    	rollAngle+=1;
    }
    else{
    	rollAngle-=1;
    }

    if (rollAngle >= 180){
    	clockwise = false;
    }
    else if(rollAngle <= -180){
    	clockwise = true;
    }
	setRoll(rollAngle);
  },50);




}
function generatePitchValue(){
	var pitchAngle = 0;
	var upward = true;

	setInterval(function(){
    if(upward == true){
    	pitchAngle +=1;
    }
    else{
    	pitchAngle-=1;
    }

    if (pitchAngle >= 90){
    	upward = false;
    }
    else if(pitchAngle <= -90){
    	upward = true;
    }
	setPitch(pitchAngle);
	},50);
}