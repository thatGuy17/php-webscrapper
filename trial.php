<?php

	include 'webscrap.php';
	include 'analyzer.php';

	$webpage = new WebScrap("https://www.euriskconsulting.co.ke/service");
	// $webpage = new WebScrap("http://www.goal.com/en-ke");
	$domDoc = $webpage->createDOMDocument();
	$domXPath = $webpage->createDOMXpath();

	$analyzer = new Analyzer($domXPath);
	$elements = $analyzer->scrapPage();

	header('Content-Type: application/json');
	echo $elements;

?>