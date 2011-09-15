<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >
<title>XBMC - Movies</title>

<script type="text/javascript" src="lib/js/prototype.js"></script>
<script type="text/javascript" src="lib/js/scriptaculous/scriptaculous.js"></script>
<script type="text/javascript" src="lib/js/highslide/highslide-full.js"></script>
<script type="text/javascript" src="lib/js/reflection.js"></script>
<script type="text/javascript" src="lib/js/reflex.js"></script>
<script type="text/javascript" src="lib/js/jscolor/jscolor.js"></script>
<script type="text/javascript" src="lib/js/main.js"></script>

<link rel="stylesheet" type="text/css" href="lib/js/highslide/highslide.css" />
<link rel="stylesheet" type="text/css" href="lib/css/main.css" />
<link rel="stylesheet" type="text/css" href="lib/css/movies.css" />

<?php

include('functions.php');
include('classes.php');
include('constants.php');
include('reflect_v3.php');
/*
print(realpath(readlink(getMovieDatabsePath())));

$blup = is_readable(getMovieDatabsePath());
print("is_readable: ");
private var_dump($blup);
print("<br/>");
$blup = filetype(getMovieDatabsePath());
print("filetype: ");
private var_dump($blup);
print("<br/>");
$blup = is_dir(getMovieDatabsePath());
print("is_dir: ");
private var_dump($blup);
print("<br/>");
print("the string: ".getMovieDatabsePath());
print("<br/><br/><br/><br/>");
*/
$db = new dbCon(getMovieDatabsePath());
$moviesArray = $db->query('SELECT * FROM movie ORDER BY c00 ASC');
$movies = buildMovies($moviesArray);


$sameNetwork = false;
if($_SERVER['REMOTE_ADDR'] == getExternalIP()){
	$sameNetwork = true;
}


?>

</head>
<body>
<?php
	//phpinfo();
?>
<div id="right_navi">
	<a href="tvshows.php">
		TvShows&rarr;
	</a>
</div>
<div id="mainContainer">

<h1>My Movies - <?php print(count($movies));?></h1>
<input id="colorPicker" class="color"  onchange="updateColorWrapper(this.color.toString())"/>
<input id="flowButton" type="button" name="flow" value="Make it Flow" onclick="colorCirqleWrapper(this)"/>

<div id="movies">

<?php 
	$counter = 1;
	foreach($movies as $movie){ 
		if($counter == 1){
			print('<div class="movie_side_left"></div>');
		}
	
?>
<div class="movie" id="movie_<?php print($movie->getID()) ?>">
	<input class="movieID" type="hidden" value="<?php print($movie->getID()) ?>"/>
	<div class="thumbnail">
		<div class="newLogo" id="isNew_<?php print($movie->getID()) ?>"></div>
		<a name="movie_<?php print($movie->getID()) ?>" href="<?php print($movie->getFanartPath()); ?>" class="highslide" onclick="return hs.expand(this)">
			<p class="hiddenName"><?php print($movie->getTitle()); ?></p>
			<img src="<?php print($movie->getThumbnailPath()); ?>" onmouseover="focusStyle($('name_<?php print($movie->getID()) ?>'))" onmouseout="unFocusStyle($('name_<?php print($movie->getID()) ?>'))"/>
		</a>
		<div class="highslide-caption">
			<h2><?php print($movie->getTitle()); ?></h2>
			<p><?php print($movie->getPlot()); ?></p>
			<br/>
			<div class="movieStats">
				<b>Year: </b><?php print($movie->getYear()); ?><br/>
				<b>Rating: </b>
					<div class="ratingWrapper">
						<div class="ratingBar" style="width:<?php print(calculateRatingSize($movie->getRating()))?>px;"></div>
						<div class="starsLayer" title="<?php print($movie->getRatingString())?>" ></div>
					</div><br/>
				<b>Runtime: </b><?php print($movie->getRuntime()); ?> min<br/>
				<b>Language: </b><img src="img/flags/<?php print($movie->getLanguage()); ?>_flag.png"/> in <?php print($movie->getAudioChannels()); ?><br/>
				<b>Resolution:</b> <?php print($movie->getResolution()); ?><br/>
				<i>Play Count: <?php print($movie->getPlayCount()); ?></i><br/>
				<?php
				if($sameNetwork){
					print('<b>Download: ');
					print('<a href="download.php?download='.$movie->getPath().'" title="Download: '. basename($movie->getPath()) .'"><img src="img/download.png" alt=""/></a>');
					print('</b>');
				}
				?>
			</div>
		</div>
		<?php
			$imgInfo['img'] = $movie->getThumbnailPath();
			$imgInfo['fade_end'] = 1;
			$imgInfo['height'] = "40%";
			makeReflection($imgInfo);
		?>
		<img class="reflection" src="img/reflectionsCache/reflection_<?php print($movie->getThumbnailName()); ?>"/>
	</div>
	<div class="h1_wrapper" id="name_<?php print($movie->getID()) ?>" style="display:none;"><span><?php print($movie->getTitle(2)); ?></span></div>
</div>
<?php 
		if($counter < 4){
			$counter++;
		}else{
			print('<div class="movie_side_right"></div>');
			$counter=1;
		}
	}
	if($counter != 1){
		print('<div class="movie_side_right"></div>');
	}
?>
</div>
<div class="clear"></div>
</div>
<SCRIPT LANGUAGE="javascript">
	// higlight setting from editor
	hs.graphicsDir = 'lib/js/highslide/graphics/';
	hs.showCredits = false;
	hs.dimmingOpacity = 0.5;
	hs.captionOverlay.position = 'rightpanel';
	hs.allowSizeReduction = true;
	hs.fadeInOut = true;

	checkCookies();
</SCRIPT>
</body>
</html>
