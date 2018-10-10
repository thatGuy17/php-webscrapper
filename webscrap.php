<?php
	/**
	* Class to get the DOM or DOMX of any page 
	*/
	class WebScrap {

		private $_url;

		function __construct($url) {
			unset($this->_url);
			$this->_url = array();
			(is_array($url)) ? $this->_url = $url : array_push($this->_url, $url);
			set_error_handler("WebScrap::customErrorHandler");
		}

		function __destruct() {}

		// sets the value of _url
		public function setURL($url){
			unset($this->_url);
			$this->_url = array();
			(is_array($url)) ? $this->_url = $url : array_push($this->_url, $url);
		}

		// gets the value of _url
		public function getURL(){
			return $this->_url;
		}

		// 
		public static function customErrorHandler($errorNumber, $errorMessage, $errorFile, $errorLine){
			echo "<b>Error:</b> [$errorNumber] $errorMessage<br>";
  			echo "Script Terminated in <b>" . $errorFile . "</b> on line <b>" . $errorLine . "</b>";
			die();
		}

		// This function will check if the URL is a valid URL
		private function validateURL(){
			$urls = array();
			foreach ($this->getURL() as $url) {
				if (filter_var($url, FILTER_VALIDATE_URL)){
					array_push($urls, $url);
				} else {
					trigger_error("Error: " . $url . " is an invalid URL");
				}
			}
			$this->setURL($urls);
			return $urls;
		}

		// Initialize cURL and return the result
		private function initializeCURL(){
			$urls = $this->validateURL();
			// Initialize, execute and close the curl session+
			$ch = array();
			$mh = curl_multi_init();
			
			$i = 0;
			foreach ($urls as $url) {
				$ch[$i] = curl_init();
				
				curl_setopt_array(
					$ch[$i], 
					array(
						CURLOPT_URL => $url,
						CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36",
						CURLOPT_FOLLOWLOCATION => true,
						CURLOPT_COOKIESESSION => true,
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_SSL_VERIFYPEER => false,
						CURLOPT_REFERER => $url,
						CURLOPT_VERBOSE => true
					)
				);

				curl_multi_add_handle($mh, $ch[$i]);

				$i++;
			}

			$active = null;

			do {
				curl_multi_exec($mh, $active);
			} while ($active);

			$webpages = array();

			$a = 0;
			foreach ($ch as $ch) {
				$webpages[$a] = curl_multi_getcontent($ch);
				curl_multi_remove_handle($mh, $ch);
				$a++;
			}

			curl_multi_close($mh);

			return $webpages;
		}

		// Create a DOMDocument and store the curl output in it
		public function createDOMDocument(){
			$webPages = $this->initializeCURL();
			$documents = array();
			foreach ($webPages as $webPage) {
				if ($webPage == FALSE){
					trigger_error("Invalid webpage");
				} else {
					$doc = new \DOMDocument();
					libxml_use_internal_errors(true);
					$doc->loadHTML($webPage);
					array_push($documents, $doc);
				}
			}
			return $documents;
		}

		//Create a DOMXpath and store the DOMDocument in it.
		public function createDOMXpath(){
			$xpath = array();
			foreach ($this->createDOMDocument() as $dom) {
				array_push($xpath, new \DOMXpath($dom));
			}
			return $xpath;
		}
	}
?>
