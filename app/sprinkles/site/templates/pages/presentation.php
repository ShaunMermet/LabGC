<?php

?>
<html>
  <head>
    <script src="https://aframe.io/releases/0.6.1/aframe.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="{{assets.url('assets://userfrosting/js/aframe-animation-component.min.js')}}"></script>
    <script src="//cdn.rawgit.com/donmccurdy/aframe-extras/v3.13.1/dist/aframe-extras.min.js"></script>
  </head>
  <body>
    <a-scene>
		<a-assets>
			<img id="groundTexture" src="{{assets.url('assets://userfrosting/images/floor.jpg')}}">
	    	<img id="skyTexture" src="{{assets.url('assets://userfrosting/images/sky3b.jpg')}}">
	    	<a-asset-item id="odm-obj" src="{{assets.url('assets://userfrosting/model/odm_textured_model.obj')}}"></a-asset-item>
	    	<a-asset-item id="odm-mtl" src="{{assets.url('assets://userfrosting/model/odm_textured_model.mtl')}}"></a-asset-item>
	    	<a-asset-item id="logo" src="{{assets.url('assets://userfrosting/model/logoCentered.ply')}}"></a-asset-item>
	    	<a-asset-item id="drone" src="{{assets.url('assets://userfrosting/model/LAB470_V4_Quadcopter.ply')}}"></a-asset-item>
	    	<a-asset-item id="prop" src="{{assets.url('assets://userfrosting/model/prop_enroute_ver19_centeredBlack.ply')}}"></a-asset-item>
	    	<a-asset-item id="torus" src="{{assets.url('assets://userfrosting/model/TorusColored.ply')}}"></a-asset-item>

	    	<img id="Slide1" src="{{assets.url('assets://userfrosting/images/presentation/Draft_Cosmos_Public_presentation_1.png')}}">
	    	<img id="Slide2" src="{{assets.url('assets://userfrosting/images/presentation/Draft_Cosmos_Public_presentation_2.png')}}">
	    	<img id="Slide3" src="{{assets.url('assets://userfrosting/images/presentation/Draft_Cosmos_Public_presentation_4.png')}}">
	    	<img id="Slide4" src="{{assets.url('assets://userfrosting/images/presentation/Draft_Cosmos_Public_presentation_5.png')}}">
	    	<img id="Slide5" src="{{assets.url('assets://userfrosting/images/presentation/Draft_Cosmos_Public_presentation_6.png')}}">
	    	<img id="Slide6" src="{{assets.url('assets://userfrosting/images/presentation/Draft_Cosmos_Public_presentation_7.png')}}">
	    	<img id="Slide7" src="{{assets.url('assets://userfrosting/images/presentation/Draft_Cosmos_Public_presentation_8.png')}}">
	  	</a-assets>
	  	<a-entity id="camera" camera="userHeight: 0" universal-controls="fly: true;movementEasingY: 15;" rotation="-22.2 81.87 0" position="-28.3 9.73 28.91"
	    	animation__p="property: position; dir: normal; dur: 2000;
                           easing: linear; loop: false; startEvents: startAnim"
			animation__r="property: rotation; dir: normal; dur: 2000;
                           easing: linear; loop: false; startEvents: startAnim"
	    > 
		    <a-entity ply-model="src: #prop" position="-0.3 0.3 -0.3" scale="0.001 0.001 0.001"
			    animation__r1="property: rotation; dir: reverse; dur: 1000;
	                           easing: linear; loop: true; to: 0 360 0"
		  	></a-entity>
		  	<a-entity ply-model="src: #prop" position="0.3 0.3 -0.3" scale="0.001 0.001 0.001"
			    animation__r1="property: rotation; dir: normal; dur: 1000;
	                           easing: linear; loop: true; to: 0 360 0"
		  	></a-entity>
	    </a-entity>
	    <a-cylinder id="ground" src="#groundTexture" radius="30" height="0.1" scale="3 3 3"></a-cylinder>
	  	<a-sky id="background" src="#skyTexture" theta-length="90" radius="30" scale="-3 3 3"></a-sky>
	  	<a-entity ply-model="src: #drone" position="0 5.208 0" scale="0.005 0.005 0.005"></a-entity>
	  	<a-entity obj-model="obj: #odm-obj; mtl: #odm-mtl" rotation="-84.2 90.12 -90.1" position="0 29 0"></a-entity>
	  	<a-entity ply-model="src: #logo" position="-35.6 6.5 27.36" 
	  		animation__r1="property: rotation; dir: normal; dur: 6000;
                           easing: linear; loop: true; to: 0 360 0"
	  	></a-entity>
	  	<a-image src="#Slide1" scale="2 2 2" width="9.60" height="5.40" position="-40 7 -16" rotation="-36 34 0">
  		 	<a-entity text="value: 1;align: center;side:double;" position="-4 3 0" scale="10 10 1"
  		 		animation__r1="property: rotation; dir: normal; dur: 6000;
                       easing: linear; loop: true; to: 0 360 0"
       		></a-entity>
       		<a-entity ply-model="src: #torus" position="-4 3 0" scale="0.3 0.3 0.3"></a-entity>
	  	</a-image>
	  	<a-image src="#Slide2" scale="2 2 2" width="9.60" height="5.40" position="20 15 -15" >
	  		<a-entity text="value: 2;align: center;side:double;" position="-4 3 0" scale="10 10 1"
  		 		animation__r1="property: rotation; dir: normal; dur: 6000;
                       easing: linear; loop: true; to: 0 360 0"
       		></a-entity>
       		<a-entity ply-model="src: #torus" position="-4 3 0" scale="0.3 0.3 0.3"></a-entity>
	  	</a-image>
	  	<a-image src="#Slide3" scale="0.2 0.2 0.2" width="9.60" height="5.40" position="40.6 5.1 3.3" >
	  		<a-entity text="value:3;align: center;side:double;" position="-4 3 0" scale="10 10 1"
  		 		animation__r1="property: rotation; dir: normal; dur: 6000;
                       easing: linear; loop: true; to: 0 360 0"
       		></a-entity>
       		<a-entity ply-model="src: #torus" position="-4 3 0" scale="0.3 0.3 0.3"></a-entity>
	  	</a-image>
	  	<a-image src="#Slide4" scale="2 2 2" width="9.60" height="5.40" position="33.08 10.94 -37.7" rotation="26.47 0 0">
	  		<a-entity text="value: 4;align: center;side:double;" position="-4 3 0" scale="10 10 1"
  		 		animation__r1="property: rotation; dir: normal; dur: 6000;
                       easing: linear; loop: true; to: 0 360 0"
       		></a-entity>
       		<a-entity ply-model="src: #torus" position="-4 3 0" scale="0.3 0.3 0.3"></a-entity>
	  	</a-image>
	  	<a-image src="#Slide5" scale="1 1 1" width="9.60" height="5.40" position="14.130 86.055 -6.740" rotation="-90.012 12.261 0">
	  		<a-entity text="value: 5;align: center;side:double;" position="-4 3 0" scale="10 10 1"
  		 		animation__r1="property: rotation; dir: normal; dur: 6000;
                       easing: linear; loop: true; to: 0 360 0"
       		></a-entity>
       		<a-entity ply-model="src: #torus" position="-4 3 0" scale="0.3 0.3 0.3"></a-entity>
	  	</a-image>
	  	<a-image src="#Slide6" scale="237.045 237.045 237.045" width="9.60" height="5.40" position="737.945 1 702.395" rotation="-90 0 0">
	  		<a-entity text="value: 6;align: center;side:double;color:#000000" position="-4 3 0" scale="10 10 1"
  		 		animation__r1="property: rotation; dir: normal; dur: 6000;
                       easing: linear; loop: true; to: 0 360 0"
       		></a-entity>
       		<a-entity ply-model="src: #torus" position="-4 3 0" scale="0.3 0.3 0.3"></a-entity>
	  	</a-image>
	  	<a-image src="#Slide7" scale="1 1 1" width="9.60" height="5.40" position="-3.309 7.032 -1.352" rotation="-24.809 130.233 -0.057">
	  		<a-entity text="value: 7;align: center;side:double;" position="-4 3 0" scale="10 10 1"
  		 		animation__r1="property: rotation; dir: normal; dur: 6000;
                       easing: linear; loop: true; to: 0 360 0"
       		></a-entity>
       		<a-entity ply-model="src: #torus" position="-4 3 0" scale="0.3 0.3 0.3"></a-entity>
	  	</a-image>
	</a-scene>
	<script type="text/javascript" src="{{assets.url('assets://userfrosting/js/presentation01.js')}}"></script>
  </body>
</html>
