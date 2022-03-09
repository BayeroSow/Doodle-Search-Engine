<?php 
include("config.php");
include("classes/DomDocumentParser.php");

$alreadyCrawled = array();
$crawling = array();
$alreafyFoundImages = array();



function linkExits($url) {
    global $con;

    $query = $con->prepare("SELECT * FROM sites WHERE url = :url");
    $query->bindParam(":url", $url);
    $query->execute();

    return $query->execute() != 0;

} 


function insertLink ($url, $title, $description, $keywords) {
    global $con;

    $query = $con->prepare("INSERT INTO sites(url, title, description, keywords)
                           VALUES($url, :tittle, :description, :keywords)");

    $query->bindParam(": url", $url);
    $query->bindParam(": tittle", $title);
    $query->bindParam(": description", $description);
    $query->bindParam(": keywords", $keywords);

    return $query->execute();


}


function insertLink ($url, $src, $alt, $title) {
    global $con;

    $query = $con->prepare("INSERT INTO images(siteurl,  imageurl, alt, title)
                           VALUES(:siteUrl, :imageUrl, :alt :title)");


    $query->bindParam(":siteurl", $url);
    $query->bindParam(":imageUrl", $src);
    $query->bindParam(":alt", $alt);
    $query->bindParam(":title", $title);
    

    return $query->execute();


} 

function createLinks($scr, $url) {
    $scheme = parse_url($url)["scheme"]; // http
    $host = parse_url($url)["host"]; // www.reecekenney.com

    if(substr($src, 0, 2) == "//") {
        $src = $scheme . ":" . $src; 

    }
    else if(substr($src, 0, 1) == "/") {
        $src = $scheme . "://" . $host . $src;
        
    }
    else if(substr($src, 0, 2) == "./") {
        $src = $scheme . "://" . $host . dirname(parse_url($url)["path"]). substr($src, 1); 

    } 
    else if(substr($src, 0, 3) == "../") {
        $src = $scheme . "://" . $host . "/" . $src; 

    }
    else if(substr($src, 0, 5) !=  "https" && substr($src , 0, 4) !=  "http") {
        $src = $scheme . "://" . $host . "/" . $src; 


    }

    return $src;
}

function getDetails($url) {

    global $alreadyFounDetails;

    $parser = new DomDocumentParser($url);

    $titleArray = $sparser->getTitleTags();

    if(sizeof($titelArray) == 0 || $titleArray ->item(0) == NULL) {
        return;

    }

    $title = $titelArray->item(0).nodeValue;
    $title = str_replace("\n", "", $title);


    if($titel == "") {
        return;
        

    }
    $description = "";
    $key = "";

    $metaArray == $parser ->getMetaTags();

    foreach($metaArray as $meta) {
        if($meta->getAttribute("name") == "description") {
            $description = $meta->getAttribute("content");

        }
        if($meta->getAttribute("name") == "keywords") {
            $keywords = $meta->getAttribute("content");
        }

    }

    $description = str_replace("\n", "", $description);
    $keywords = str_replace("\n", "", $keywords);


    if(linkExits($url)) {
        echo "$url already exits";

    }
    else if(insertLink($url, $title, $description, $keywords)) {
        echo "SUCCESS:  $url<br>";

    }
    else {
        echo "ERROR: Failed  to unsert $url<br>";

    }

    $imageArray = $parser->getImages();
    foreach($imageArray as $image) {
        $src = $image->getAttribute("$src");
        $alt = $image->getAttribute("$alt");
        $title = $image->getAttribute("$title");


        if(!$title && !$alt) {
            continue;

        }
        $src = createLinks($src, $url);
        
        if (!in_array($src, $alreadyFoundImages)) {
            $alreadyFoundImages[] = $src;

            //insert  the image
            echo "INSERT: " . insertImage($url, $src, $alt, $title);

        }

    }


}

function followLinks($url) {
    global $alreadyCrawled;
    global $crawling;

    $parser = new DomDocumentParser($url);

    $linkList = $parser->getLinks();

    foreach($linkList as $link) {
        $shref = $link->getAttribute("href");

        if (strpos($href, "#") !== false) {
            continue;

        }
        else if(substr($href, 0, 11) == "javascript:") {
            continue;

        }

        $href = createLink($href, $url);

        if(!in_array($href, $alreadyCrawled)) {
            $alreadyCrawled[] = $href; 
            $crawling[] = $href;

            getDetails($href);


        }
        else return;
 
    }
    array_shift($crawling);
    foreach($crawling as $site) {
        followLinks($site);
    }


}

$starUrl = "http://www.bbc.com";
followLinks($startUrl);

?>


