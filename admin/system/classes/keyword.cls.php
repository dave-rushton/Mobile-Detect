<?php

class searchHeader {
	var $search_id = 0;
	var $searchWord = '';
	var $occurances = 0;
	var $contentPages = array();
	var $altTagPages = array();
	var $seoUrlPages = array();
	var $titleTagPages = array();
	var $keywordPages = array();
	var $descriptionPages = array();
	var $internalPages = array();
	var $altTags = 0;
	var $seoUrls = 0;
	var $titleTags = 0;
	var $metaKeywords = 0;
	var $metaDescriptions = 0;
	var $internalLinks = 0;
	var $wordCount = 0;
}
class searchDetail {
	
	var $search_id = 0;
	var $TblNam = '';
	var $Tbl_ID = 0;
	var $contentPageName = '';
	var $wordCount = 0;
	var $occurances = 0;
	var $seoUrl = false;
	var $titleTag = false;
	var $metaKeyword = false;
	var $metaDescription = false;
	var $altTags = 0;
	var $internalLinks = 0;
	
	function cleanHaystack($haystack) {
		//
		// Format workable string
		//
		$find = array('/\r/','/\n/','/\s\s+/','/\-/','/\,/');
		$replace = array(' ',' ',' ',' ');
		$workString = $haystack;
		$workString = preg_replace('/[>][<]/', '> <', $workString);
		$workString = strip_tags($workString, '<img><a>');
		$workString = strtolower($workString);
		$workString = preg_replace($find, $replace, $workString);
		$workString = trim($workString);
		return $workString;
	}
	
	function getWordCount($needle,$haystack) {
		
		$needle = strtolower($needle);
		$workString = $this->cleanHaystack($haystack);
		
		$this->occurances += substr_count($workString, $needle);
		
		$wordCount = explode(' ', $workString);
		$this->wordCount += count($wordCount);
		
		$this->inLinks($needle,$haystack);
		$this->inAltTags($needle,$haystack);
		
	}
	
	function inSEO($needle,$haystack) {
		$needle = strtolower($needle);
		$haystack = $this->cleanHaystack($haystack);
		
		if ( $this->seoUrl == false ) {
			$this->seoUrl = (substr_count($haystack, $needle) > 0) ? true : false;
			
			$this->occurances += (substr_count($haystack, $needle) > 0) ? 1 : 0;
			
			//echo substr_count($haystack, $needle).' '.$this->seoUrl.'<br>';
		}
	}
	function inTitle($needle,$haystack) {
		$needle = strtolower($needle);
		$haystack = $this->cleanHaystack($haystack);
		$this->titleTag = (substr_count($haystack, $needle) > 0) ? true : false;
		
		$this->occurances += (substr_count($haystack, $needle) > 0) ? 1 : 0;
		
	}
	function inMetaKeyword($needle,$haystack) {
		$needle = strtolower($needle);
		$haystack = $this->cleanHaystack($haystack);
		$this->metaKeyword = (substr_count($haystack, $needle) > 0) ? true : false;
		
		$this->occurances += (substr_count($haystack, $needle) > 0) ? 1 : 0;
		
	}
	function inMetaDescription($needle,$haystack) {
		$needle = strtolower($needle);
		$haystack = $this->cleanHaystack($haystack);
		$this->metaDescription = (substr_count($haystack, $needle) > 0) ? true : false;
		
		$this->occurances += (substr_count($haystack, $needle) > 0) ? 1 : 0;
		
	}
	function inAltTags($needle,$haystack) {
		
		$needle = strtolower($needle);
		$haystack = $this->cleanHaystack($haystack);
		
		$preg = "/<img.*? alt=(\"|')(.*?)(\"|').*?\/>/i";  
		preg_match_all($preg,$haystack, $links);
		
		for ($l=0;$l<count($links[2]);$l++) {
			
			$haystack = $this->cleanHaystack($links[2][$l]);
			$this->altTags += substr_count($haystack, $needle);
			
		}
		
	}
	function inLinks($needle,$haystack) {
		
		$needle = strtolower($needle);
		
		$preg = "/<a.*? href=(\"|')(.*?)(\"|').*?>(.*?)<\/a>/i";  
		preg_match_all($preg,$haystack, $links);
		
		for ($l=0;$l<count($links[4]);$l++) {
			
			$haystack = $this->cleanHaystack($links[4][$l]);
			$this->internalLinks += substr_count($haystack, $needle);
			
		}
		
	}
	
}

?>