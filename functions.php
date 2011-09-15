<?php


function get_string_between($string, $start, $end){ 
    $string = " ".$string; 
    $ini = strpos($string,$start); 
    if ($ini == 0) return ""; 
    $ini += strlen($start); 
    $len = strpos($string,$end,$ini) - $ini; 
    return substr($string,$ini,$len); 
} 


function int32_to_hex($value) {
  $value &= 0xffffffff;
  return str_pad(strtoupper(dechex($value)), 8, "0", STR_PAD_LEFT);
}

function buildMovies($moviesArray){	    
	$db = new dbCon(getMovieDatabsePath());
	foreach ($moviesArray as $movieArray) {
		$moviesObjs[] = buildMovie($movieArray,$db);
	}
	return $moviesObjs;
}

function buildMovie($movieArray,$db){
	$movie = new Movie($movieArray);
    $fileID = $movie->getFileID();
    $movieFileInfo = getFileInfo($fileID,$db);
   	
   	$imageLocations = getImageLocations($movieFileInfo);
   	$movie->setImageLocations($imageLocations);
    
    $movie->setPlayCount($movieFileInfo['playCount']);
    $movie->setHeight($movieFileInfo['height']);
    $movie->setWidth($movieFileInfo['width']);
    $movie->setAudioChannels($movieFileInfo['audioChannels']);
    $movie->setAudioCodec($movieFileInfo['audioCodec']);
    $movie->setFilePath($movieFileInfo['path']);
    
    return $movie;
}



// TVSHOWS !!!!!! #############

function buildTvShows($tvShowsArray, $buildSeason = false){
	//var_dump($tvShowsArray);	    
	$db = new dbCon(getMovieDatabsePath());
	foreach ($tvShowsArray as $tvShowArray) {
		$tvShowsObjs[] = buildTvShow($tvShowArray,$db,$buildSeason);
	}
	return $tvShowsObjs;
}

function buildTvShow($tvShowArray,$db,$buildSeason = false){
	$tvShow = new TvShow($tvShowArray);
	// print("theID:".$tvShow->getID());
	$tvShowID = $tvShow->getID();
	$tvShowPathIDRow = $db->query("SELECT * FROM tvshowlinkpath WHERE idShow is ". $tvShowID);
	$tvShowInfo['path'] = getPath($tvShowPathIDRow[0]['idPath'],$db);
	$tvShowInfo['stack'] = "";
	
	if($buildSeason){
		$tvShowSeasons = buildSeasons($tvShowID,$db);
	    $tvShow->setSeasons($tvShowSeasons);
	    $seasons = $tvShow->getSeasons();
		foreach($seasons as $season){
			$fileInfos['path'] = "season".$tvShowInfo['path']."Season ".$season->getNumber();
			$fileInfos['stack'] = "";
			$imageLocations = getImageLocations($fileInfos);
			$season->setImageLocations($imageLocations);
		}
		
	}
	
	//print("thepath: ".$tvShowPath."<br/>");
	$imageLocations = getImageLocations($tvShowInfo);
	$tvShow->setImageLocations($imageLocations);
	
	$tvShow->setFilePath($tvShowInfo['path']);
	
	
	return $tvShow;
}

// i know its not like the others... it also needs the the path of the tv show to calculate the
// seasons thumb ... we could do it in buildTvShow with a nother foreach loop ... i will do that ^^
function buildSeasons($tvShowID,$db){
	$seasons;
	$episodeIDs = $db->query("SELECT * FROM tvshowlinkepisode WHERE idShow is ". $tvShowID);
	foreach($episodeIDs as $episodeID){
		$episodeArrayRow = $db->query("SELECT * FROM episode WHERE idEpisode is ". $episodeID['idEpisode']);
		$episodeObj = buildEpisode($episodeArrayRow[0],$db);
		$episodeSeason = $episodeObj->getSeason();
		
		if(isset($seasons[$episodeSeason])){
			$seasons[$episodeSeason]->addEpisode($episodeObj);
		}else{
			$season = new Season($episodeSeason);
			$season->addEpisode($episodeObj);
			$seasons[$episodeSeason] = $season;
		}
	}
	return $seasons;
}


function buildEpisode($episodeArray,$db){
	$episode = new Episode($episodeArray);
	
    $fileID = $episode->getFileID();
    
    $episodeFileInfo = getFileInfo($fileID,$db);
    $episodeFilePath = $episodeFileInfo['path'];
    
   	$images = getImageLocations($episodeFileInfo);
   	$episode->setImageLocations($images);
    
    
    $episode->setPlayCount($episodeFileInfo['playCount']);
    $episode->setHeight($episodeFileInfo['height']);
    $episode->setWidth($episodeFileInfo['width']);
    $episode->setAudioChannels($episodeFileInfo['audioChannels']);
    $episode->setAudioCodec($episodeFileInfo['audioCodec']);
    $episode->setFilePath($episodeFileInfo['path']);
    

	
	return $episode;
}

//helper functions ##############
// it also gets the fanart well kinda
function getImageLocations($fileInfo){
	$filePathCrc = getCrc($fileInfo['path']);
    $filePathCrcFolder = substr($filePathCrc, 0,1); // we need the first char -> somthing like "4"
    $image['thumbnail']['name'] = $filePathCrc.'.tbn'; // just add the ".tbn"
    $image['thumbnail']['path'] = getThumbnailPath().$filePathCrcFolder.'/'.$image['thumbnail']['name']; // fullpath ! well as full as getThumbnailPath() was set
    if($fileInfo['stack']!=""){
		$fileStackCrc = getCrc($fileInfo['stack']);
		$image['fanart']['name'] = $fileStackCrc.'.tbn';
		$image['fanart']['path'] = getThumbnailFanartPath().$fileStackCrc.'.tbn';
    }else{
    	$image['fanart']['name'] = $image['thumbnail']['name'];
    	$image['fanart']['path'] = getThumbnailFanartPath().$image['fanart']['name'];
    }
    
    if(is_file($image['thumbnail']['path'])){
    	return $image;	
    }else if(is_file(getThumbnailPath().$filePathCrcFolder.'/auto-'.$image['thumbnail']['name'])){
    	$image['thumbnail']['name'] = 'auto-'.$image['thumbnail']['name'];
    	$image['thumbnail']['path'] = getThumbnailPath().$filePathCrcFolder.'/'.$image['thumbnail']['name'];
    	return $image;
    }else{
    	$image['thumbnail']['name'] = "unknowThumb.png";
    	$image['thumbnail']['path'] = "unknowThumb.png";
    	$image['fanart']['name'] = "unknowThumb.png";
    	$image['fanart']['path'] = "unknowThumb.png";
    	return $image;
    }
}


function getCrc($path){
	$execute ='./crc_ala_xbmc.rb "'. strtolower($path) .'"';
	$pathCrc = shell_exec($execute);
	return substr($pathCrc, 0,-1); // you get a space (" ") to much so we clean it up
}

// returns the file path ..either if stack ore single file
// need file id if single file to get full path
// todo
function getFilePath($fileRow,$db){
    $fileNameStackTest = get_string_between($fileRow[0]['strFilename'],"stack://"," ,"); // if there is a substring it was a stack
    if($fileNameStackTest != ""){
		$filePath['path'] = $fileNameStackTest;
		$filePath['stack'] = $fileRow[0]['strFilename'];
    	return $filePath;
    }else{ // it wasnt a stack so we have build every thing together
    	$fileName = $fileRow[0]['strFilename'];
    }
    $filePathID = $fileRow[0]['idPath'];
    $fileDir = getPath($filePathID,$db);
	$filePath['path'] = $fileDir.$fileName;
	$filePath['stack'] = "";
    return $filePath;
}

function getPath($idPath,$db){
    $pathRow = $db->query('SELECT * FROM path WHERE idPath is ' . $idPath);
    return $pathRow[0]['strPath'];
    
}



function getFileResolution($fileID,$db){
    $fileResolutionRow = $db->query('SELECT * FROM streamdetails WHERE idFile is ' . $fileID . ' AND iStreamType is 0');
	//print("Resolution: ". $fileResolutionRow[0]['iVideoWidth'].' x '. $fileResolutionRow[0]['iVideoHeight'] .'<br/>');
	if(isset($fileResolutionRow[0]['iVideoWidth'])){
		$fileResolution['width'] = $fileResolutionRow[0]['iVideoWidth'];
	}else{
		$fileResolution['width'] = 0;
	}
	if(isset($fileResolutionRow[0]['iVideoHeight'])){
		$fileResolution['height'] = $fileResolutionRow[0]['iVideoHeight'];
	}else{
		$fileResolution['height'] = 0;
	}
	return $fileResolution;
}

function getFileAudio($fileID,$db){
    $fileAudioRow = $db->query('SELECT * FROM streamdetails WHERE idFile is ' . $fileID . ' AND iStreamType is 1');
	//print("Resolution: ". $fileResolutionRow[0]['iVideoWidth'].' x '. $fileResolutionRow[0]['iVideoHeight'] .'<br/>');
	if(isset($fileAudioRow[0]['iAudioChannels'])){
		$fileAudio['channels'] = $fileAudioRow[0]['iAudioChannels'];
	}else{
		$fileAudio['channels'] = 0;
	}
	if(isset($fileAudionRow[0]['strAudioCodec'])){
		$fileAudio['codec'] = $fileAudioRow[0]['strAudioCodec'];
	}else{
		$fileAudio['codec'] = "unknown";
	}
	return $fileAudio;
}

function getFileInfo($fileID,$db){
	$fileRow = $db->query('SELECT * FROM files WHERE idFile is ' . $fileID);
	$fileInfoResolution = getFileResolution($fileID,$db);
	$fileInfoAudio = getFileAudio($fileID,$db);
	
	$fileInfo['height'] = $fileInfoResolution['height'];
	$fileInfo['width'] = $fileInfoResolution['width'];
    $fileInfo['audioChannels'] = $fileInfoAudio['channels'];
	$fileInfo['audioCodec'] = $fileInfoAudio['codec'];
	$pathInfo = getFilePath($fileRow,$db);
    $fileInfo['path'] = $pathInfo['path'];
    $fileInfo['stack'] = $pathInfo['stack'];
	$fileInfo['playCount'] = $fileRow[0]['playCount'];
    return $fileInfo;
}

function getLanguageString($filePath){
		
		$result = stripos($filePath,'eng - ger');
		if($result !== false){
			return "eng_ger";
		}
		
		$result = stripos($filePath,'ger - eng');
		if($result !== false){
			return "ger_eng";
		}
		
		$result = stripos($filePath,'ger - jap');
		if($result !== false){
			return "ger_jap";
		}
		
		$result = stripos($filePath,'jap - ger');
		if($result !== false){
			return "jap_ger";
		}
		$result = stripos($filePath,'jap - eng');
		if($result !== false){
			return "jap_eng";
		}
		$result = stripos($filePath,'eng - jap');
		if($result !== false){
			return "ger_jap";
		}
		
		$result = stripos($filePath,'eng');
		if($result !== false){
			return "eng";
		}
		
		$result = stripos($filePath,'ger');
		if($result !== false){
			return "ger";	
		}		
		
		$result = stripos($filePath,'jap');
		if($result !== false){
			return "jap";	
		}		
		return "nono";
}
function order_array_num($array, $key, $order = "ASC"){
        $tmp = array();
        foreach($array as $akey => $array2)
        {
            $tmp[$akey] = $array2[$key];
        }
       
        if($order == "DESC")
        {arsort($tmp , SORT_NUMERIC );}
        else
        {asort($tmp , SORT_NUMERIC );}

        $tmp2 = array();       
        foreach($tmp as $key => $value)
        {
            $tmp2[$key] = $array[$key];
        }       
       
        return $tmp2;
} 

// number compare function
function cmp($a, $b){
    if ($a == $b) {
        return 0;
    }
    return ($a < $b) ? -1 : 1;
}

function getExternalIP(){
	$execute ='./externalIP.sh';
	$ip = shell_exec($execute);
	$ipClean = substr($ip, 1, strlen($ip)-2); // we get a string with some spaces from the sh script just cleaning that, it could be done in the script itself but i trust php ^^
	return $ipClean;
}

function calculateRatingSize($rating){
	$widthOfStarLayer = 180;
	$widthOfStarBar = intval(($rating/10)*$widthOfStarLayer);
	return $widthOfStarBar;
}

?>