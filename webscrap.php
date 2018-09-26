<?php
	/**
	* Class to get the DOM or DOMX of any page 
	*/
	class WebScrap {

		private $_url;

		function __construct($url) {
			$this->_url = $url;
			set_error_handler("WebScrap::customErrorHandler");
		}

		function __destruct() {}

		public function setURL($url){
			$this->_url = $url;
		}

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
			if (filter_var($this->getURL(), FILTER_VALIDATE_URL)){
				return $this->getURL();
			} else{
				trigger_error("Error: Invalid URL");
			}
		}

		// Initialize cURL and return the result
		private function initializeCURL(){
			$url = $this->validateURL();

			// Initialize, execute and close the curl session+
			$ch = curl_init();
	        curl_setopt_array(
	        	$ch, 
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
	        $result = curl_exec($ch);

			// Check if curl returns an invalid result and throw an error if any
			if (curl_errno($ch)) { 
		        curl_close($ch);
		        trigger_error((string)curl_error($ch));
			} elseif (trim($result) == "") {
		        curl_close($ch);
		        trigger_error("Empty String Returned From cURL");
			} else {
		        curl_close($ch); 
				return $result;
			}
		}

		// Create a DOMDocument and store the curl output in it
		public function createDOMDocument(){
			$webPage = $this->initializeCURL();
			if ($webPage == FALSE){
				trigger_error("Invalid webpage");
			} else {
				$doc = new \DOMDocument();
				libxml_use_internal_errors(true);
				$doc->loadHTML($webPage);
				return $doc;
			}
		}

		//Create a DOMXpath and store the DOMDocument in it.
		public function createDOMXpath(){
			return new \DOMXpath($this->createDOMDocument());
		}
	}
?>