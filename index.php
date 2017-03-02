<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta name="google-site-verification" content="LwDz4hv2eE2c5m5xWWDzoQxu2exz_opdbv4SpbHdli8" />
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Room radio, room vibes and more stuff</title>
      <link rel="stylesheet" type="text/css" href="mystyle.css">
   </head>
   <body>
      <div  class="video">
         <video  autoplay="true" loop="loop"  id="bgvid">
            <source src="http://83.212.116.204/radio.mp4" type="video/mp4">
         </video>
      </div>
      <div id="mask">
         <div><img id="onAir" src="http://83.212.116.204/OnAir.jpg"alt="on Air" /></div>
      </div>
      <div id="rightSide">
         <div id="player" >
            <audio id="music">
               <source src="http://83.212.116.204:8000/listen.mp3" type="audio/mpeg" />
            </audio>
            <img src="http://83.212.116.204/image.jpg" id="image" />
            <div id="metadata">
               <marquee id="marquee" scrollamount="3">
               </marquee>
            </div>
            <button id="pButton" class="play" onmouseup="playAudio()"></button>
            <button type="submit" id="winamp" class="winamp" onclick="winamp('http://83.212.116.204/roomRadio.m3u')"></button>
            <button id="muteButton" class="mute" onmousedown="muteAudio()"></button>
            <button id="youtube" class="youtube" onmousedown="youtube()"></button>
            <div id="volumeContainer"  onmousemove="test()" >
               <div id="volume"> </div>
            </div>
         </div>
         <div class="container">
            <div class="chat">
               <b>
                  <div id="chatZone" name="chatZone"></div>
               </b>
			   <div id="container">
					<label for="msg"  id="name">
						<a id="profile" hidden>Guest:</a>
					</label>
					<div id="login">
						<fb:login-button size="medium" scope="public_profile,email" onlogin="checkLoginState();"> </fb:login-button>
					</div>
					<div id="disclaimer">
						To chat with your facebook name, we don't care to post anything!
					</div>
            </div>
               <form onsubmit="chat.sendMsg(); return false;">
                  <input type="text" id="msg" name="msg" autofocus="true" placeholder="Type Your Message Here" />
                  <input type="submit" />
               </form>
            </div>
			
            
         </div>
      </div>
      <script>
         (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
         (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
         m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
         })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
         
         ga('create', 'UA-40210398-2', 'auto');
         ga('send', 'pageview');
         
      </script>
		  <script type="text/javascript" src="chat.js"></script>
      <script>
         var music = document.getElementById('music');
         var volume = document.getElementById('volume');
         var volumeContainer=document.getElementById('volumeContainer');
         var rect = volumeContainer.getBoundingClientRect();
         var indic =false;
         volumeContainer.style.width="88px";
         volume.style.width="88px";
         var maxVol = volume.style.width;
         
         volumeContainer.addEventListener("mousedown", function(){
         	indic=true;
         })
         
         document.body.addEventListener("mouseup", function(){
         	indic=false;
         })
         
         function playAudio() {
         	if (music.paused) {
         		
         		music.play();
         		pButton.className = "";
         		pButton.className = "pause";
         	} else { 
         		music.pause();
         		pButton.className = "";
         		pButton.className = "play";
         	}
         }
         playAudio();
         
         function muteAudio(){
         	if (music.muted==false)
         	{
         		music.muted=true;
         		muteButton.className = "";
         		muteButton.className = "mute";
         	}
         	else
         	{
         		music.muted=false;
         		muteButton.className = "";
         		muteButton.className = "unmute";		
         	}
         }
         
         function clicked(){
         	indic=true;
         }
         function unclicked(){
         	indic=false;
         	if ( parseInt(volume.style.width,10)> parseInt(volumeContainer.style.width,10)){
         		volume.style.width=volumeContainer.style.width;
         	}
         }
         
         function test() {
         	if (indic==true)
         	{
         		if ( parseInt(volume.style.width,10)<= parseInt(volumeContainer.style.width,10)){
            			volume.style.width= event.pageX-rect.left+"px";
            			var percent = parseInt(volume.style.width,10)/parseInt(maxVol,10);
            			music.volume = percent;
         		}
         		else{volume.style.width=volumeContainer.style.width;}		
            }
         }
      </script>
      <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
      <script>
         $(document).ready(function(){
            
                 $.get("http://roomradio.com/metadata.php", function(data, status){
                     var res = data.split("metaData=",2);
         			document.getElementById('marquee').innerHTML=res[1];
                 });
            
         });
         function get_request() {
            
                 $.get("http://roomradio.com/metadata.php", function(data, status){
                     var res = data.split("metaData=",2);
         			var metadata= res[1].split("onAir=",2);
         			//window.alert(metadata[1]+metadata[0]);
         			document.getElementById('marquee').innerHTML=metadata[0];
         			if (metadata[1]=="true"){
         			document.getElementById('onAir').style.visibility='visible';
         			}
         			else
         			document.getElementById('onAir').style.visibility='hidden';
         			 setTimeout( get_request, 15000 ); // <-- when you ge a response, call it  //        again after a 4 second delay                                       
                 });   
         }
         get_request();  // <-- start it off
         ////////////////////////
         function youtube()
         {
         	var url="http://www.youtube.com/results?search_query="
         	window.open(url+document.getElementById('marquee').innerHTML,'_blank');
         }
         ///////////////////////
         function winamp(file)
         {
         	window.open(file,'_blank');
         }
         
         ///Image size
         var image=document.getElementById('image');
         var initialWidth=parseInt(image.style.width,10);
         var initialHeight=parseInt(image.style.height,10);
         
         image.style.height = "220px";
         	if (initialWidth/initialHeight*parseInt(image.style.height,10) < 300)
         		image.style.width = initialWidth/initialHeight*parseInt(image.style.height,10)+"px";
         		else
         		image.style.width="230px";
         		// image.src="http://roomradio.com/image.jpg?nocache="+n;
         ///////////refresh Image
         function refresh_image() {
            		
                 	var d = new Date();
            			var n = d.getTime();
                     image.src="http://83.212.116.204/image.jpg?nocache="+n;
         			 setTimeout( refresh_image, 20000 ); // <-- when you ge a response, call it  //        again after a 4 second delay                                       
         }
         refresh_image();
      </script>
      <script>
         (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
         (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
         m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
         })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
         
         ga('create', 'UA-40210398-2', 'auto');
         ga('send', 'pageview');
         
      </script>
      <script>
         // This is called with the results from from FB.getLoginStatus().
         function statusChangeCallback(response) {
           console.log('statusChangeCallback');
         
           // The response object is returned with a status field that lets the
           // app know the current login status of the person.
           // Full docs on the response object can be found in the documentation
           // for FB.getLoginStatus().
           if (response.status === 'connected') {
           	var access_token=response.authResponse.accessToken;
         testAPI(access_token);
         // testAPI();
           } else if (response.status === 'not_authorized') {
             // The person is logged into Facebook, but not your app.
             document.getElementById('status').innerHTML = 'Please log ' +
               'into this app.';
           } else {
             // The person is not logged into Facebook, so we're not sure if
             // they are logged into this app or not.
             document.getElementById('status').innerHTML = 'Please log ' +
               'into Facebook.';
           }
         }
         
         // This function is called when someone finishes with the Login
         // Button.  See the onlogin handler attached to it in the sample
         // code below.
         function checkLoginState() {
           FB.getLoginStatus(function(response) {
             statusChangeCallback(response);
           });
         }
         
         window.fbAsyncInit = function() {
         FB.init({
           appId      : '771848356225352',
           cookie     : true,  // enable cookies to allow the server to access 
                               // the session
           xfbml      : true,  // parse social plugins on this page
           version    : 'v2.1' // use version 2.1
         });
         
         // Now that we've initialized the JavaScript SDK, we call 
         // FB.getLoginStatus().  This function gets the state of the
         // person visiting this page and can return one of three states to
         // the callback you provide.  They can be:
         //
         // 1. Logged into your app ('connected')
         // 2. Logged into Facebook, but not your app ('not_authorized')
         // 3. Not logged into Facebook and can't tell if they are logged into
         //    your app or not.
         //
         // These three cases are handled in the callback function.
         
         FB.getLoginStatus(function(response) {
          if (response.status === 'connected') {
         //  console.log(response.authResponse.accessToken);
         }
         });
         
         };
         
         // Load the SDK asynchronously
         (function(d, s, id) {
           var js, fjs = d.getElementsByTagName(s)[0];
           if (d.getElementById(id)) return;
           js = d.createElement(s); js.id = id;
           js.src = "//connect.facebook.net/en_US/sdk.js";
           fjs.parentNode.insertBefore(js, fjs);
         }(document, 'script', 'facebook-jssdk'));
         
         // Here we run a very simple test of the Graph API after login is
         // successful.  See statusChangeCallback() for when this call is made.
         function testAPI(access_token) {
           console.log('Welcome!  Fetching your information.... ');
           FB.api('/me', function(response) {
         name=response.name;
         profile=response.link;
         chat_container="http://roomradio.com/chatContainer.php?name="+encodeURIComponent(name)+"&profile="+profile+"&access_token="+access_token;
         $('#container').load(chat_container);
         return false;
           });
         }
      </script>
      </div>
   </body>
</html>