<?php
	/**
	* Class to process the DOMDocument and/or DOMXpath and return all videos, images, links and scripts
	*/
	class Analyzer {
		
		private $_xpath;

		/**
		 * Constructor requires on variable of type xpath or an array with xpath values
		*/
		function __construct($xpath){
			unset($this->_xpath);
			$this->_xpath = array();
			(is_array($xpath)) ? $this->_xpath = $xpath : array_push($this->_xpath, $xpath);
		}

		function __destruct() {}

		/**
		 * Sets the value of _xpath
		*/
		public function _setXpath($xpath){
			unset($this->_xpath);
			$this->_xpath = array();
			(is_array($xpath)) ? $this->_xpath = $xpath : array_push($this->_xpath, $xpath);
		}

		/**
		 * Gets the value of _xpath.
		*/
		public function getXpath(){
			return $this->_xpath;
		}

		/**
		 * Loops through the array and saves the sources of the elements in an array
		*/
		private function getSources($array){
			$temp = array();
			foreach ($array as $arr) {
				$src = $arr->getAttribute('src');
				if (isset($src) && $src !== "") {
					array_push(
						$temp, 
						array(
							"source" => $arr->getAttribute('src')
						)
					);	
				}
			}

			return $temp;
		}


		/**
		 * Used to scrap the page for its title, links, images, videos, iframes and scripts
		*/
		public function scrapPage(){
			$xpaths = $this->getXpath();
			$results = array();
			
			foreach ($xpaths as $xpath) {
				$pageLinks = array();
				$pageImages = array();
				$pageIframes = array();
				$pageVideos = array();
				$pageScripts = array();
				
				$title = $xpath->query('//title');
				$links = $xpath->query('//a');
				$images = $xpath->query('//img');
				$videos = $xpath->query('//video');
				$iframes = $xpath->query('//iframe');
				$scripts = $xpath->query("//script");

				$pageTitle = $title->item(0)->textContent;
				
				foreach ($links as $link) {
					array_push(
						$pageLinks, 
						array(
							"text" => $link->textContent, 
							"href" => $link->getAttribute('href')
						)
					);
				}

				foreach ($images as $image) {
					array_push(
						$pageImages, 
						array(
							"caption" => $image->getAttribute('alt'),
							"source" => $image->getAttribute('src')
						)
					);
				}

				$pageVideos = $this->getSources($videos);
				$pageIframes = $this->getSources($iframes);
				$pageScripts = $this->getSources($scripts);

				$elements = array(
					"title" => $pageTitle,
					"images" => $pageImages,
					"videos" => $pageVideos,
					"iframes" => $pageIframes,
					"links" => $pageLinks,
					"scripts" => $pageScripts
				);	

				array_push($results, $elements);
			}

			return json_encode($results);
		}
	}
?>
