function focusStyle(obj){
	//var movieName = "name_"+objID;
	//alert(obj);
	obj.appear({ duration: 0.3 });
	/*
	new Effect.Morph(movieName, {
  		style: 'background: #fff; color: #000;', // CSS Properties
		duration: 0.2 // Core Effect properties
	});
	*/
	
	
}

function unFocusStyle(obj){
	
	//var movieName = "name_"+objID;
	obj.fade({ duration: 0.3 });
	/*
	new Effect.Morph(movieName, {
  		style: 'background: #000; color: #fff;', // CSS Properties
		duration: 0.2 // Core Effect properties
	});
	//$(movieName).style.background = 'none';
	*/
}

var red = 0;
var green = 0;
var blue = 0;
var redUp = true;
var greenUp = true;
var blueUp = true;
var step = 5;

var aktiv;
var is_on = false;

function colorCirqleWrapper(obj){
	if(!is_on){
		updateColorWrapper($('colorPicker').value);
		aktiv = window.setInterval("colorCirqle()", 60);
		is_on = true;
		$('flowButton').value = "Make it Stop";
	}else{
		window.clearInterval(aktiv);
		is_on = false;
		color = rgbToHex(red,green,blue);
		$('colorPicker').value = color;
		$('colorPicker').style.backgroundColor = '#'+color;	
		updateColor(color);
		$('flowButton').value = "Make it Flow";
	}
	
}
function normaliseColors(){
	// todo!! 
	
	if(red == 255){
		if(blue >= 127){
			blue = 255;
			green = 0;
			return true;
		}
		if(green >= 127){
			green = 255;
			blue = 0;
			return true;
		}
		green = 0;
		blue = 0;
	}
	
	
	if(green == 255){
		if(blue >= 127){
			blue = 255;
			red = 0;
			return true;
		}
		if(red >= 127){
			red = 255;
			blue = 0;
			return true;
		}
		red = 0;
		blue = 0;
	}	
	
	if(blue == 255){
		if(green >= 127){
			green = 255;
			red = 0;
			return true;
		}
		if(red >= 127){
			red = 255;
			green = 0;
			return true;
		}
		red = 0;
		green = 0;
		
	}
}

function colorCirqle(){
	
	
	limiter = 255;
	
	if(red < 255 && green == 0 && blue == 0){
		doRedUp();
	}
	if(red == 255 && green < 255 && blue == 0){
		doGreenUp();
	}
	if(red >= 0 && green == 255 && blue == 0){
		doRedDown();
	}
	if(red == 0 && green == 255 && blue < 255){
		doBlueUp();
	}
	if(red == 0 && green >= 0 && blue == 255){
		doGreenDown();
	}
	if(red < 255 && green == 0 && blue == 255){
		doRedUp();
	}
	if(red == 255 && green == 0 && blue >= 0){
		doBlueDown();
	}
	color = rgbToHex(red,green,blue);
	updateColor(color);

}
function rgbToHex(red,green,blue){
	return int2hex(red)+int2hex(green)+int2hex(blue);
}
function updateColorWrapper(color){
	
	red = hexToR(color);
	green = hexToG(color);
	blue = hexToB(color);
	normaliseColors();
	updateColor(color);
}


function updateColor(color){
	var movies = getElementsByStyleClass('movie');
	var movie_side_right = getElementsByStyleClass('movie_side_right');
	var movie_side_left = getElementsByStyleClass('movie_side_left');
	//var ratingBar = getElementsByStyleClass('ratingBar');
	
	for (var i = 0; i < movies.length; i++){
		movies[i].style.borderColor = '#'+color;
		movies[i].style.backgroundColor = '#'+color;
	}

	for (var i = 0; i < movie_side_right.length; i++){
		movie_side_right[i].style.borderColor = '#'+color;
		movie_side_right[i].style.backgroundColor = '#'+color;
	}

	for (var i = 0; i < movie_side_left.length; i++){
		movie_side_left[i].style.borderColor = '#'+color;
		movie_side_left[i].style.backgroundColor = '#'+color;
	}
	/* not for know ... the div is beeing moved/hidden/gone when once zoomed
		so only the first time it is updated then the color can not be changed
	for (var i = 0; i < ratingBar.length; i++){
		ratingBar[i].style.backgroundColor = '#'+color;
	}
	*/
	putColorCookie(color);
}

function updatePanel(obj){
	
	var movies = getElementsByStyleClass('movie');
	var movie_side_right = getElementsByStyleClass('movie_side_right');
	var movie_side_left = getElementsByStyleClass('movie_side_left');
	//alert(obj.checked);
	if(obj.checked){
		
		for (var i = 0; i < movies.length; i++){
			movies[i].style.backgroundImage = "url('cover_verlauf_end.png')";
		}

		for (var i = 0; i < movie_side_right.length; i++){
			movie_side_right[i].style.backgroundImage = "url('cover_verlauf_right2_end.png')";
		}
	
		for (var i = 0; i < movie_side_left.length; i++){
			movie_side_left[i].style.backgroundImage = "url('cover_verlauf_left2_end.png')";
		}
	
	}else{
		for (var i = 0; i < movies.length; i++){
			movies[i].style.backgroundImage = "url('cover_verlauf.png')";
		}

		for (var i = 0; i < movie_side_right.length; i++){
			movie_side_right[i].style.backgroundImage = "url('cover_verlauf_right2.png')";
		}
	
		for (var i = 0; i < movie_side_left.length; i++){
			movie_side_left[i].style.backgroundImage = "url('cover_verlauf_left2.png')";
		}	
	}
		
}
// cookie handling



expireTime = 7; // in days
expireTimeLong = 365; // in days
color_cookie_name = "color";
knownMovies_cookie_name = "knownMovies";
newMovies_cookie_name = "newMovies";


function checkCookies(){
	ceckForNewMovies();
	setColorFromCockie();
}

function putColorCookie(color) {
	setCookie(color_cookie_name,color,expireTimeLong);
}


function setColorFromCockie(){
	color = getCookie(color_cookie_name);
	if (color!=null && color!=""){
		color = color;
	}else{
		color = "FFFFFF";
	}
	$('colorPicker').value = color;
	updateColor(color);	
}


function ceckForNewMovies(){
	
	var cleanNewMovies = cleanOldNewMovies();
	//alert(cleanNewMovies)
	var knownMovies_then = getCookie(knownMovies_cookie_name);
	if(knownMovies_then!=null && knownMovies_then!=""){
		var newMovies = getNewMoviesFrom(getAllMovieIDs(),knownMovies_then);
  		if(newMovies.length > 0){
			for (var n = 0; n < newMovies.length; n++){
		  		setCookie("newMovie_"+newMovies[n],newMovies[n],expireTime);
		  	}
  			//alert("found new movies: " + newMovies.join(','));
  			newMovies_then = getCookie(newMovies_cookie_name);
  			if(newMovies_then!=null && newMovies_then!="" && cleanNewMovies!=null){
  				var value = cleanNewMovies.join(',') + "," + newMovies.join(',');
  				//alert(value);
				setCookie(newMovies_cookie_name,value,expireTimeLong);
  			}else{
				setCookie(newMovies_cookie_name,newMovies.join(','),expireTimeLong);
  			}
  		}
	}
	setKnownMovieCookie();
	toMarkAsNew = getCookie(newMovies_cookie_name);
	if(toMarkAsNew!=null && toMarkAsNew!=""){
		markNewMovies(toMarkAsNew.split(','));
	}
}


function cleanOldNewMovies(){
	toCheck = getCookie(newMovies_cookie_name);
	if(toCheck!=null && toCheck!=""){
		toCheck_array = toCheck.split(',');
		var cleanNewMovies = new Array();
		for (var n = 0; n < toCheck.length; n++){
			toCheckSingle = getCookie("newMovie_"+toCheck_array[n]);
			//alert("found a cookie from:" +toCheckSingle);
			if(toCheckSingle!=null && toCheckSingle!=""){
				cleanNewMovies[cleanNewMovies.length] = toCheckSingle;
			}
	  	}
	  	return cleanNewMovies;
	}
}

function getNewMoviesFrom(knownMovies_now,knownMovies_then){
	var knownMovies_now_array = knownMovies_now.split(",");
	var oldMovies = new Array();
	var newMovies = new Array();
	for (var n = 0; n < knownMovies_now_array.length; n++){
		var index = knownMovies_then.indexOf(knownMovies_now_array[n]);
		if(index > -1){
			oldMovies[oldMovies.length] = knownMovies_now_array[n];
		}else{
			newMovies[newMovies.length] = knownMovies_now_array[n];
		}
	}
	return newMovies;
}

function markNewMovies(newMovies){
	for (var n = 0; n < newMovies.length; n++){
		if($("isNew_"+newMovies[n])!=null){
  			$("isNew_"+newMovies[n]).style.display = 'block';
		}
  	}
}


function setKnownMovieCookie(){
	knownMovieIDs = getAllMovieIDs();
	setCookie(knownMovies_cookie_name,knownMovieIDs,expireTimeLong);
	
}

// cookie seter and geter

function setCookie(c_name,value,expiredays){
	var exdate=new Date();
	exdate.setDate(exdate.getDate()+expiredays);
	document.cookie=c_name+ "=" +escape(value)+((expiredays==null) ? "" : ";expires="+exdate.toUTCString());
}


function getCookie(c_name){
if (document.cookie.length>0)
  {
  c_start=document.cookie.indexOf(c_name + "=");
  if (c_start!=-1){
    c_start=c_start + c_name.length+1;
    c_end=document.cookie.indexOf(";",c_start);
    if (c_end==-1) c_end=document.cookie.length;
    return unescape(document.cookie.substring(c_start,c_end));
    }
  }
return "";
}
// helper functions
function getAllMovieIDs(){
	movieInputs = getElementsByStyleClass("movieID");
	var movieIDs;
  	for (var n = 0; n < movieInputs.length; n++){
  		if(n == 0){
  			movieIDs = movieInputs[n].value;
  		}else{
  			movieIDs = movieIDs +","+ movieInputs[n].value;
  		}
  	}
	return movieIDs;
}

function getElementsByStyleClass (className) {
  var all = document.all ? document.all :
    document.getElementsByTagName('*');
  var elements = new Array();
  for (var e = 0; e < all.length; e++)
    if (all[e].className == className)
      elements[elements.length] = all[e];
  return elements;
}


function doRedUp(){
	red = red + step;
	if(red > 255){
		red = 255;
	}
}
function doGreenUp(){
	green = green + step;
	if(green > 255){
		green = 255;
	}
}
function doBlueUp(){
	blue = blue + step;
	if(blue > 255){
		blue = 255;
	}
}

function doRedDown(){
	red = red - step;
	if(red < 0){
		red = 0;
	}
}
function doGreenDown(){
	green = green - step;
	if(green < 0){
		green = 0;
	}
}
function doBlueDown(){
	blue = blue - step;
	if(blue < 0){
		blue = 0;
	}
}

function int2hex(number){
	hex = number.toString(16).toUpperCase();
	if(hex.length == 1){
		return "0"+hex;
	}
	return hex;

}


function hexToR(h) {return parseInt((cutHex(h)).substring(0,2),16)}
function hexToG(h) {return parseInt((cutHex(h)).substring(2,4),16)}
function hexToB(h) {return parseInt((cutHex(h)).substring(4,6),16)}
function cutHex(h) {return (h.charAt(0)=="#") ? h.substring(1,7):h}

function getStyle(el,styleProp)
{
	var x = document.getElementById(el);
	return getStyleObj(x,styleProp);
}
function getStyleObj(x,styleProp)
{
	if (x.currentStyle)
		var y = x.currentStyle[styleProp];
	else if (window.getComputedStyle)
		var y = document.defaultView.getComputedStyle(x,null).getPropertyValue(styleProp);
	return y;
}

// tvshows

function makeSmaller(obj,prozent){
	if(prozent==null)prozent = 90;
	var widthOfObj = parseInt(getStyleObj(obj,'width'));
	var newWidth = parseInt((widthOfObj / 100)*prozent);
	var newStyle = 'width: '+newWidth+'px;'
	new Effect.Morph(obj.id, {
		style: newStyle, // CSS Properties
		duration: 0.1 // Core Effect properties
	});
}

function makeBigger(obj){
	var widthOfObj = parseInt(getStyleObj(obj,'width'));
	var newWidth = parseInt((widthOfObj / 100)*110);
	var newStyle = 'width: '+newWidth+'px;'
	new Effect.Morph(obj.id, {
		style: newStyle, // CSS Properties
		duration: 0.1 // Core Effect properties
	});	
}

function makeSize(obj,newWidth,newHeight){
	if(newWidth==null)newWidth = -1;
	if(newHeight==null)newHeight = -1;
	var newWidthString = new String();
	var newHeightString = new String();
	
	if(newWidth != -1){	
		var newWidthString = "width: "+newWidth+"px;"
	}
	if(newHeight != -1){
		newHeightString = "height: "+newHeight+"px;";	
	}
	var newStyle = newWidthString+newHeightString;
	//alert("newStyle: "+newStyle);
	new Effect.Morph(obj.id, {
		style: newStyle, // CSS Properties
		duration: 0.1 // Core Effect properties
	});
	
}

function setBorderSize(obj,size){
	if(size==null)size = 0;
	var newStyle = "border: "+size+"px;";
	new Effect.Morph(obj.id, {
		style: newStyle, // CSS Properties
		duration: 0.1 // Core Effect properties
	});
}


// season tv show thing !!!

function activateSeason(seasonNumber){
	resizeSeasonsDesign()
	var episodesId = 'episodes_'+seasonNumber;
	var seasonId = 'season_'+seasonNumber;
	var seasonPic = 'seasonPic_'+seasonNumber;
	var seasonOffset = 0;
	if($(seasonId).offsetParent){
		seasonOffset = $(seasonId).offsetTop;
	}
	
	var episodes = getElementsByStyleClass ('episodes');
	var seasonLinks = getElementsByStyleClass ('seasonLinks');
	var seasonPics = getElementsByStyleClass ('seasonPic');
	
	for (var i = 0; i < episodes.length; i++){
			episodes[i].style.display = "none";
			seasonPics[i].style.display = "none";
			seasonLinks[i].style.color = "#B6B6B6";
	}
	$(episodesId).appear({ duration: 0.4 });
	$(seasonPic).appear({ duration: 0.4 });
	//$(seasonId).style.color = "#000";
	
	var newPos = seasonOffset + 26;

	var newPosString = 'top:'+ newPos +'px;';
	new Effect.Morph('selectorWrapper', {
  						style:  newPosString, // CSS Properties
  						duration: 0.3 // Core Effect properties
	});
	
	new Effect.Morph(seasonId, {
  						style:  "color:#fff;", // CSS Properties
  						duration: 0.3 // Core Effect properties
	});
	
}

function getDivHeight(id){
	if(document.getElementById(id).clientHeight){
        return document.getElementById(id).clientHeight;
	}
	return document.getElementById(id).offsetHeight;
}
function resizeSeasonsDesign(){
	$('seasonsDesign').style.height = getStyle('seasons','height');
	$('seasonsDesign').style.height = getDivHeight('seasons');
}

