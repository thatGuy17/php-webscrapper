# PHP WebScrapper
- A simple web scrapping tool in PHP that utilizes cURL.
- If you have an ideas for more functionality let me know. :smile:

## How to use webscrap.php
1. First, include the file in your project.
```
  <?php
    ...
    include 'webscrap.php';
    ...
  ?>
```

2. Next, create an instance of the webscrapper class and pass the URL of the page to be scrapped to the constructor.
```
  <?php
    ...
    //Parse the URL as the parameter in the constructor
    $webpage = new WebScrap("https://www.github.com");
    ...
  ?>
```

Or pass an array of urls.
```
  <?php
    ...
    //Parse an array of URL's as the parameter in the constructor
    $urls = array("https://www.github.com", "https://www.bing.com", "https://www.outlook.com", "https://www.linkedin.com")
    $webpage = new WebScrap($urls);
    ...
  ?>
```

3. Next, create a DOM Document or a DOM XPath (depending on your preference).
```
  <?php
    ...
    //returns an array with value(s) of type dom document
    $domDoc = $webpage->createDOMDocument(); 
    
    //returns an array with value(s) of type dom xpath
    $domXPath = $webpage->createDOMXpath(); 
    ...
  ?>
```
## How to use analyzer.php
This class must be used after webscrapper.php has been used and the domXPath has been created.
1. First, include the file in your project.
```
  <?php
    ...
    include 'analyzer.php';
    ...
  ?>
```

2. Next, create an instance of the Analyzer class and pass the domXPath to the constructor.
```
  <?php
    ...
    $analyzer = new Analyzer($domXPath);
    ...
  ?>
```

3. Next, scrap the page for its images, links, videos, iframes, scripts and title
```
  <?php
    ...
    // returns an array of the pages images, links, videos, iframes, scripts and title for each domXPath provied
    $elements = $analyzer->scrapPage();
    ...
  ?>
```
