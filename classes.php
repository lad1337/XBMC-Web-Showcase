<?php
class MotionPicture{
	
	var $id; //idMovie
	var $title; //c00
	var $plot; //c01
	var $plotOutline; //c02
	var $tagline; //c03
	var $ratingVotes; //c04
	var $rating; //c05
	var $writers; //c06
	var $yearReleased; //c07
	var $thumbnails; //c08
	var $imdbID; //c09
	var $unknown0; //c10
	var $runtime; //c11
	var $mpaaRating; //c12
	var $unknown1; //c13
	var $genre; //c14
	var $director; //c15
	var $originalMovieTitle; //c16
	var $unknown2; //c17
	var $studio; //c18
	var $trailerURL; //c19
	var $fanartURLs; //c20
	var $idFile; //idFile
	
	
	var $fileDir;
	var $fileName;
	var $filePath;
	
	var $height;
	var $width;
	var $audioChannels;
	var $audioCodec;
	var $playCount;
	
	var $thumbnailPath; // something like /a/a39832.tbn
	var $thumbnailName; // something like /a39832.tbn
	var $fanartPath;
	
	var $imageLocations;
	
	// deprcated
	private function cleanThumbnailsUrl(){
		$this->thumbnails = get_string_between($this->thumbnails, '<thumb preview="', '">');
	}
	
	// seter ----------
	
	public function setFilePath($filePath){
		$this->filePath = $filePath;
	}
	public function setFileName($fileName){
		$this->fileName = $fileName;
	}
	public function setFileDir($fileDir){
		$this->fileDir = $fileDir;
	}
	public function setHeight($height){
		$this->height = $height;
	}
	public function setWidth($width){
		$this->width = $width;
	}
	public function setAudioChannels($audioChannels){
		$this->audioChannels = $audioChannels;
	}
	public function setAudioCodec($audioCodec){
		$this->audioCodec = $audioCodec;
	}
	public function setPlayCount($playCount){
		$this->playCount = $playCount;
	}
	public function setThumbnailPath($thumbnailPath){
		$this->thumbnailPath = $thumbnailPath;
	}
	public function setThumbnailName($thumbnailName){
		$this->thumbnailName = $thumbnailName;
	}
	public function setFanartPath($fanartPath){
		$this->fanartPath = $fanartPath;
	}
	public function setImageLocations($imageLocations){
		$this->imageLocations = $imageLocations;
	}
	// geter ----------
	public function getID(){
		return $this->id;	
	}
	

	public function getYear(){
		if(isset($this->yearReleased)){
			return $this->yearReleased;
		}else{
			return "unknown";
		}
	}
	public function getHeight(){
		if(isset($this->height)){
			return $this->height;
		}else{
			return 0;
		}
	}
	public function getRuntime(){
		if(isset($this->runtime)){
			return $this->runtime;
		}else{
			return "unknown";
		}
	}
	public function getWidth(){
		if(isset($this->width)){
			return $this->width;
		}else{
			return 0;
		}
	}
	public function getResolution(){
		if(isset($this->width) && isset($this->height )&& $this->width != 0 && $this->height != 0){
			return $this->width.' x '.$this->height;
		}else{
			return 'unknown';
		}
	}
	public function getAudioChannels(){
		if(isset($this->audioChannels)){
			switch ($this->audioChannels){
			case 0;
				return "unknown";
				break;
			case 1:
				return "Mono";				
				break;
			case 2:
				return "Stereo";
				break;
			case 3:
				return "2.1";
				break;
			case 6:
				return "5.1";
				break;
			default:
				return $this->audioChannels;
			}
		}else{
			return "unknown";
		}
	}
	public function getAudioCodec(){
		if(isset($this->audioCodec)){
			return $this->audioCodec;
		}else{
			return "unknown";
		}
	}
	public function getPlayCount(){
		if(isset($this->playCount)){
			return $this->playCount;
		}else{
			return 0;
		}
	}
	
	public function getName($mode = 0){
		return 	$this->getTitle($mode);
	}
	
	// 0 default and does not cut at all
	// 1 cuts it after 20 chars if longer than 23
	// 2 cuts it after 36 chars if longer than 40
	// anything else will cut at that point
	public function getTitle($mode = 0){
		$title = $this->title;
		
		switch ($mode){
			case 0:
				return $title;				
				break;
			case 1:
				if(strlen($title) > 23){
					return substr($title, 0,20).'&hellip;';
				}else{
					return $title;
				}
				break;
			case 2:
				if(strlen($title) > 40){
					return substr($title, 0,36).'&hellip;';
				}else{
					return $title;
				}
				break;
			default:
				if(strlen($title) > $mode){
					return substr($title, 0,$mode).'&hellip;';
				}else{
					return $title;
				}
		}
	}
	public function getThumbnailPath(){
		return $this->imageLocations['thumbnail']['path'];
	}
	public function getThumbnailName(){
		return $this->imageLocations['thumbnail']['name'];
	}
	public function getFanartPath(){
		return $this->imageLocations['fanart']['path'];
	}
	public function getFanartName(){
		return $this->imageLocations['fanart']['name'];
	}
	public function getLanguage(){
		return getLanguageString($this->filePath);	
	}
	public function getPath(){
		if(isset($this->filePath)){
			return $this->filePath;
		}
	}
	public function getThumbnailUrl(){
		return $this->thumbnails;
	}
	public function getRatingFloat($precision){
		$rating = round((float)$this->rating,$precision);
		return $rating;
	}
	public function getRating($mode=0){
		if($mode == 1){
			return $this->getRatingString();
		}
		return $this->rating;
	}
	public function getRatingString(){
		$rating = round((float)$this->rating,2);
		if($rating <= 0){
			return "No rating";
		}else{
			return $rating . "/10";
		}
	}
	public function getPlot(){
		if($this->plot != ""){
			return $this->plot;
		}else{
			return "No plotline available.";
		}
	}
	public function getFileID(){
		return $this->idFile;
	}
	// comparer
	
	/* This is the static comparing function: */
    static function cmpByNumber($a, $b)
    {
        $al = $a->getNumber();
        $bl = $b->getNumber();
        if ($al == $bl) {
            return 0;
        }
        return ($al < $bl) ? -1 : 1;
    }
	static function cmpByID($a, $b)
    {
        $al = $a->getID();
        $bl = $b->getID();
        if ($al == $bl) {
            return 0;
        }
        return ($al < $bl) ? -1 : 1;
    }
	

	
}

class Movie extends MotionPicture{	
	
	public function __construct($array) {
	 	$this->id = $array['idMovie'];
	 	$this->title = $array['c00'];
	 	$this->plot = $array['c01'];
	 	$this->plotOutline = $array['c02'];
	 	$this->tagline = $array['c03'];
	 	$this->ratingVotes = $array['c04'];
	 	$this->rating = $array['c05'];
	 	$this->writers = $array['c06'];
	 	$this->yearReleased = $array['c07'];
	 	$this->thumbnails = $array['c08'];
	 	$this->runtime = $array['c11'];
	 	$this->idFile = $array['idFile'];
	}
	
	
}


class Episode extends MotionPicture{	
	var $season;
	var $number;
	
	public function __construct($array) {
	 	$this->id = $array['idEpisode'];
	 	$this->title = $array['c00'];
	 	$this->plot = $array['c01'];
	 	$this->plotOutline = $array['c02'];
	 	$this->tagline = $array['c03'];
	 	$this->ratingVotes = $array['c04'];
	 	$this->rating = $array['c03'];
	 	$this->writers = $array['c06'];
	 	$this->yearReleased = $array['c07'];
	 	$this->thumbnails = $array['c08'];
	 	$this->idFile = $array['idFile'];
	 	$this->season = $array['c12'];
	 	$this->number = $array['c13'];
	}
	
	public function getSeason(){
		return $this->season;
	}
	public function getNumber($mode = 0){
		$number = $this->number;
		if($mode == 0){
			return $number;
		}else{
			while(strlen($number) < $mode){
				$number = "0".$number;
				
			}
			return $number;
		}
	}	
}

class TvShow extends MotionPicture{	
	var $seriesId;
	var $network;
	var $seasons;
	
	var $pointer = 0; // int poiner of episode increases wenn getNextSeason is called
	
	public function __construct($array) {
	 	$this->id = $array['idShow'];
	 	$this->title = $array['c00'];
	 	$this->plot = $array['c01'];
	 	$this->rating = $array['c04'];
	 	$this->yearReleased = $array['c05']; // First Aired
	 	$this->thumbnails = $array['c06'];
	 	$this->genre = $array['c08'];
	 	$this->seriesId = $array['c12'];
	 	$this->network = $array['c14'];
	 	
	 	//$this->seasons[] = new Season();
	}
	
	public function setSeasons($aSeasons){
		$this->seasons = $aSeasons;
		$this->sortSeasons();
	}
	
	// at param 1 -> the episode obj
	// at param 2 -> optional index for a assosiative array BUT will also add in normal "index-int-mode"
	//				NO ability to override a episode	
	public function addSeason($episode, $index = "jhiuh76f7f454a43s54f76909hkghhh"){
		if($index == "jhiuh76f7f454a43s54f76909hkghhh"){
			$this->season[] = $episode;
		}else{
			$this->episodes[] = $episode;
			if(!isset($this->season[$index])){
				$this->season[$index] = $episode;
			}
		}	
	}
	
	// returns an episode obj
	// OR false if indx does not exist
	public function getSeason($index){
		if($index <= count($this->seasons)){
			return $this->seasons[$index];
		}else{
			print("Warning out of index in season, index: " .$index."<br/>");
			return false;
		}
	}
	// return an array with all the episodes obj's
	public function getSeasons(){
		return $this->seasons;
	}
	
	// use with coution !!!
	public function getNextSeason(){
		$this->pointer++;
		return $this->seasons[$this->pointer];
	}
	
	public function getEpisode($season,$episode){
		$seasonObj = $this->getSeason($season);
		$episodeObj = $seasonObj->getEpisode($episode);
		return $episodeObj;
	}
	
	private function sortSeasons(){
		usort($this->seasons,array( "TvShow","cmpByNumber"));
	}
	

		
}
// todo check for overlaps !!! with redefined functions
class Season extends MotionPicture{
	
	var $number;
	var $episodes; // array<Episodes>
	var $thumbnailPath;
	
	var $pointer = 0; // int poiner of episode increases wenn getNextEpisode is called
	
	
	public function __construct($aNumber = 0){
		$this->number = $aNumber;
		
	}
	public function setNumber($aNumber){
		$this->number = $aNumber;
	}
	public function setThumbnailPath($thumbnailPath){
		$this->thumbnailPath = $thumbnailPath;
	}
	
	public function getNumber($mode = 0){
		$number = $this->number;
		if($mode == 0){
			return $number;
		}else{
			while(strlen($number) < $mode){
				$number = "0".$number;
			}
			return $number;
		}
	}		
	// at param 1 -> the episode obj
	// at param 2 -> optional index for a assosiative array
	//				NO ability to override a episode
	public function addEpisode($episode, $index = "jjb487ho34984cm490498hhhhh893hr8hhhhh"){
		if($index == "jjb487ho34984cm490498hhhhh893hr8hhhhh"){
			$this->episodes[] = $episode;
		}else{
			//$this->episodes[] = $episode;
			if(!isset($this->episodes[$index])){
				$this->episodes[$index] = $episode;
			}
		}
		$this->sortEpisodes();
	}
	
	private function sortEpisodes(){
		usort($this->episodes,array( "Episode","cmpByNumber"));
	}
	
	// returns an episode obj
	// OR false if indx does not exist
	public function getEpisode($index){
		if($index <= count($this->episodes)){
			return $this->episodes[$index];
		}else{
			print("Warning out of index in episodes, index: " .$index."<br/>");
			return false;
		}
	}
	// return an array with all the episodes obj's
	public function getEpisodes(){
		return $this->episodes;
	}
	
	// use with coution !!!
	public function getNextEpisode(){
		$this->pointer++;
		return $this->episodes[$this->pointer];
	}
	
}

class dbCon{
	
	private $db;
	private $dbPath;
	private static $instance;

	public function __construct($dataBasePath = "unSet") {
		$this->dbPath = $dataBasePath; 
	 	$this->init();
	}	

	public static function singleton() {
	  if(!isset(self::$instance)) {
	    $c = __CLASS__;
	    self::$instance = new $c();
	  }
	  return self::$instance;
	} 
	
	private function init(){
		if(!isset($dataBasePath)){
			$dataBasePath = $this->dbPath;
		}
		if( $this->db = new SQLite3($dataBasePath)){
			return true;
		}else{
			die("can not open database: " . $dataBasePath);
		}	
	}
	
	function setDbPath($dataBasePath){
		$this->dbPath = $dataBasePath; 
	 	$this->init();
	}
	
	// takes a sql string as argument
	// and gives an array of found elements back .. if only one elemnt its still in a array "container"
	public function query($query){
		$result = $this->db->query($query);
		
		while ($row = $result->fetchArray()) {
			//var_dump($row);
	    	$results[] = $row;
		}
		if(isset($results)){
			return $results;
		}
		
		return array(0);
	}


}








?>