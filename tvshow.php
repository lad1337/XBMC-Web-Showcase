<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >

<script type="text/javascript" src="lib/js/prototype.js"></script>
<script type="text/javascript" src="lib/js/scriptaculous/scriptaculous.js"></script>
<script type="text/javascript" src="lib/js/highslide/highslide-full.js"></script>
<script type="text/javascript" src="lib/js/reflection.js"></script>
<script type="text/javascript" src="lib/js/reflex.js"></script>
<script type="text/javascript" src="lib/js/jscolor/jscolor.js"></script>
<script type="text/javascript" src="lib/js/main.js"></script>

<link rel="stylesheet" type="text/css" href="lib/js/highslide/highslide.css" />
<link rel="stylesheet" type="text/css" href="lib/css/main.css" />
<link rel="stylesheet" type="text/css" href="lib/css/tvshow.css" />



<script type="text/javascript">

// higlight setting from editor
hs.graphicsDir = 'lib/js/highslide/graphics/';
hs.showCredits = false;
//hs.dimmingOpacity = 0.5;
hs.easing = 'linearTween';
hs.allowSizeReduction = true;
//hs.allowMultipleInstances = false;
hs.fadeInOut = true;
hs.padToMinWidth = true;
hs.anchor = 'right'
hs.registerOverlay({
	html: '<div class="closebutton" onclick="return hs.close(this)" title="Close"></div>',
	position: 'top right',
	useOnHtml: true,
	fade: 2 // fading the semi-transparent overlay looks bad in IE
});



</script>
<?php

include('functions.php');
include('classes.php');
include('constants.php');

$db = new dbCon(getMovieDatabsePath());
$tvShowArray = $db->query('SELECT * FROM tvshow WHERE idShow is '. $_GET['id']);
$tvShows = buildTvShows($tvShowArray, true);

$sameNetwork = false;
if($_SERVER['REMOTE_ADDR'] == getExternalIP()){
	$sameNetwork = true;
}


?>
<style type="text/css">
<!--
body,
#xtraCornerTopLeft,
#xtraCornerBottomLeft,
#xtraCornerTopRight,
#xtraCornerBottomRight,
#xtraCornerBgRight,
#xtraCornerBgLeft{
	background: #000 url('<?php print($tvShows[0]->getfanartPath());?>') no-repeat center fixed;
	/*-moz-background-size: contain; ruckelt beim bewegen des selectors */ 

}
-->
</style>
<title>XBMC - <?php print($tvShows[0]->getName());?></title>
</head>
<body onLoad="activateSeason(<?php $tvShowSeasons = $tvShows[0]->getSeasons();print($tvShowSeasons[0]->getNumber()); ?>);resizeSeasonsDesign();">
<div id="left_navi">
	<a href="tvshows.php">
		&larr;TvShows
	</a>
</div>
<div id="mainContainer">


<?php

	foreach($tvShows as $key=>$tvShow){ 
?>
<h1><?php print($tvShow->getName());?></h1>
<!--<img src="<?php print($tvShow->getThumbnailPath()); ?>">//-->
<div id="singleShow">
<div id="seasonPicWrapper">
	<?php 
		foreach($tvShow->getSeasons() as $key=>$season){
	?>
	<div id="seasonPic_<?php print($season->getNumber());?>" class="seasonPic">
		<img src="<?php print($season->getThumbnailPath()); ?>" alt=""/>
	</div>			
	<?php			
		}
	?>
</div>
<div id="menuWrapper">
	<div id="selectorWrapper">
		<div id="xtraCornerBgRight"></div>
		<div id="xtraCornerBgRightDisorder"></div>
		<div id="xtraCornerTopRight"></div>
		<div id="selector"></div>
		<div id="xtraCornerBottomRight"></div>
		<div id="xtraCornerBgLeft"></div>
		<div id="xtraCornerBgLeftDisorder"></div>
		<div id="xtraCornerTopLeft"></div>
		<div id="xtraCornerBottomLeft"></div>
	</div>
	<div id="seasonsDesign"></div>
	<div id="seasons">
<?php 
	foreach($tvShow->getSeasons() as $key=>$season){
?>
		<a class="seasonLinks" id="season_<?php print($season->getNumber());?>" onClick="activateSeason(<?php print($season->getNumber());?>)">Season: <?php print($season->getNumber(2));?></a>
<?php
	}// end of getSeasons foreach
?>
	</div>	
</div>
<?php 
	}// end of tvshows foreach
?>


<?php
	foreach($tvShows as $key=>$tvShow){ 
?>
<div id="episodesWrapper">
<?php 
	foreach($tvShow->getSeasons() as $key=>$season){
?>
	<div class="episodes" id="episodes_<?php print($season->getNumber());?>" style="display:none;">
<?php 
	foreach($season->getEpisodes() as $key=>$episode){
?>
		<a href="<?php print($episode->getThumbnailPath()); ?>" class="highslide" onclick="return hs.expand(this)">
			<?php print($episode->getNumber(2));?> - <?php print($episode->getName(48));?><?php if($episode->getPlayCount() > 0){print('<span class="checker">&radic;</span>');} ?>
			
		</a>
		<div class="highslide-caption">
			<h2><?php print($episode->getName()); ?></h2>
			<p>Season <?php print($season->getNumber(2));?><br/>Episode <?php print($episode->getNumber(2));?></p>
			<p><?php print($episode->getPlot()); ?></p>
			<br/>
			<div class="episodeStats">
				<b>Year: </b><?php print($episode->getYear()); ?><br/>
				<b>Rating: </b>
					<div class="ratingWrapper">
						<div class="ratingBar" style="width:<?php print(calculateRatingSize($episode->getRating()))?>px;"></div>
						<div class="starsLayer" title="<?php print($episode->getRatingString())?>"></div>
					</div><br/>
				<b>Language: </b><img src="img/flags/<?php print($episode->getLanguage()); ?>_flag.png"/> in <?php print($episode->getAudioChannels()); ?><br/>
				<b>Resolution: </b><?php print($episode->getResolution()); ?><br/>
				<i>Play Count: <?php print($episode->getPlayCount()); ?></i><br/>
				<?php
				if($sameNetwork){
					print('<b>Download: ');
					print('<a href="download.php?download='.$episode->getPath().'" title="Download: '. basename($episode->getPath()) .'"><img src="img/download.png" alt=""/></a>');
					print('</b>');
				}
				?>
			</div>
		</div>
<?php
	}// end of getEpisodes foreach
?>
	</div>
<?php
	}// end of getSeasons foreach
?>
</div>
</div>
<?php 
	}// end of tvshows foreach
?>



<div class="clear"></div>
</div>
<script type="text/javascript">
	//activateSeason(1);
	//resizeSeasonsDesign();
</script>
</body>
</html>
