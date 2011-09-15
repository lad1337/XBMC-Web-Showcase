<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >
<title>XBMC - TvShows</title>

<script type="text/javascript" src="lib/js/prototype.js"></script>
<script type="text/javascript" src="lib/js/scriptaculous/scriptaculous.js"></script>
<script type="text/javascript" src="lib/js/highslide/highslide-full.js"></script>
<script type="text/javascript" src="lib/js/reflection.js"></script>
<script type="text/javascript" src="lib/js/reflex.js"></script>
<script type="text/javascript" src="lib/js/jscolor/jscolor.js"></script>
<script type="text/javascript" src="lib/js/main.js"></script>

<link rel="stylesheet" type="text/css" href="lib/js/highslide/highslide.css" />
<link rel="stylesheet" type="text/css" href="lib/css/main.css" />
<link rel="stylesheet" type="text/css" href="lib/css/tvshows.css" />


<script type="text/javascript">

// higlight setting from editor
hs.graphicsDir = 'lib/js/highslide/graphics/';
hs.showCredits = false;
hs.dimmingOpacity = 0.5;
hs.captionOverlay.position = 'rightpanel';
hs.fadeInOut = true;
hs.easing = 'linearTween';
hs.allowSizeReduction = true;
hs.allowMultipleInstances = false;
/*
hs.registerOverlay({
	//html: '<div class="closebutton" onclick="return hs.close(this)" title="Close"></div>',
	position: 'rightpanel',
	//useOnHtml: true,
	//fade: 2 // fading the semi-transparent overlay looks bad in IE
});
*/
</script>
<?php

include('functions.php');
include('classes.php');
include('constants.php');

$db = new dbCon(getMovieDatabsePath());
$tvShowArray = $db->query('SELECT * FROM tvshow ORDER BY c00 ASC');
$tvShows = buildTvShows($tvShowArray);
?>
</head>
<body>
<div id="left_navi">
	<a href="index.php">
		&larr;Movies
	</a>
</div>
<div id="mainContainer">
<h1>TvShows (<?php print(count($tvShows));?>)</h1>

<?php
	foreach($tvShows as $key=>$tvShow){ 
	//var_dump($tvShow)
?>
<div class="tvshowWrapper" >
	<a href="tvshow.php?id=<?php print($tvShow->getID())?>">
		<div class="tvshowOverlay" id="overlay_<?php print($tvShow->getID())?>" onmouseover="makeSmaller($('banner_<?php print($tvShow->getID())?>'),80);setBorderSize(this,4);focusStyle($('name_<?php print($tvShow->getID())?>'))" onmouseout="makeSize($('banner_<?php print($tvShow->getID())?>'),758,-1);setBorderSize(this);unFocusStyle($('name_<?php print($tvShow->getID())?>'))">
		</div>
		<p class="hiddenName"><?php print($tvShow->getName())?></p>
	</a>
	<div class="tvshow" >
		<img id="banner_<?php print($tvShow->getID())?>" src="<?php print($tvShow->getThumbnailPath()); ?>"/>					
	</div>
	<div class="tvshowNameWrapper" id="name_<?php print($tvShow->getID())?>" style="display:none;">
			<h1><?php print($tvShow->getName())?></h1>
	</div>
</div>
<?php 

	}// end of tvshows foreach

?>
</div>
<div class="clear"></div>
</div>

</body>
</html>
