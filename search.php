<?php
include("config.php");
include("classes/SearchResultProvider.php");
include("classes/imageResultProvider.php");



    if(isset($_GET["term"])) {
        $term = $_GET["term"];
    }
    else {
        exit("You must enter a search term");
    }
    $type = isset($_GET["type"]) ? $_GET["type"] : sites;
    $page = isset($_GET["page"]) ? $_GET["page"] : 1;

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>welcome to Doodle</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css" />
        <link rel="stylesheet" tape="text/css" href="assets/css/style.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" 
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" 
        crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="wrapper">
            <div class="header">

            <div class="headerContent">
                <div class="logoContainer">
                    <a href="index.php"></a>
                    <img src="assets/images/doodle.png">
                    </a>
                </div>
                <div class="searchcContainer">
                    <form action="search.php"  method="GET">
                        <div class="searchbarContainer">
                            <input type="hidden"  name="type" value="<?php echo $type?>">
                            <input class="searchBox" type="text" name="term" value="<?php echo $term?>">
                            <button class="searchButton">
                                <img src="assets/images/search-19.png">
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="tabsContainer">
                <ul class="tabList">
                    <li class="<?php echo $type == 'sites' ? 'active' : '' ?>">
                        <a href='<?php echo "search.php?term=$term&type=sites"; ?>'>
                        Sites
                        </a>
                    </li>

                    <li class="<?php echo $type == 'images' ? 'active' : '' ?>">
                        <a href='<?php echo "search.php?term=$term&type=images"; ?>'>
                        Images
                        </a>
                    </li>
                     
                </ul>
            </div>
            </div>
            <divn class="mainResultSection">
                <?php
                if($type == "sites") {
                    $resultProvider = SearchResultProvider($con);
                    $pageSize = 20;

                }
                else {
                    $resultProvider = imageResultProvider($con);
                    $pageSize = 30;

                } 

                $numResults = $resultsProvider->getNumResults($term);

                echo "<p class='resultsCount'>$numResults results found</p>";

                echo $resultsProvider ->getResultsHtml($page, $pageSize, $term);

                ?>
            </div>

            <div paginationContainer>
                <div class=pageButtons>
                    <div class="pageNumberContainer">
                        <img src="assets/images/pageStart.png" alt="">
                    </div>


                    <?php
                    
                    $pagesToshow = 10;
                    $numPages = ceil($numResults / $pageSize);
                    $pagesLeft = min($pagesToShow, $numPages);

                    $currentpage = $page - floor($pagesToShow / 2);

                    if($currentPage <1) {
                        $currenPage = 1;
                    }
                    if($currentPage + $pagesLeft > $numPages + 1) {
                        $currentPage = $numPages + 1 - $pagesLeft;

                    }

                    while(pagesLeft != 0 && $currentPage <= $numPages) {
                        if($currentPage == $page) {
                            echo "div class='pageNumberContainer'>
                                   <img src='assets/Images/pageSelected.png'>
                                   <span class='pageNumber'>$currentPage</span>   
                                </div>";
                            
                        }
                        else {
                            echo "div class='pageNumberContainer'>
                                   <a href='search.php?term=$term&type=$type&page=$currentPage'> 
                                       <img src='assets/Images/page.png'>
                                       <span class='pageNumber'>$currentPage</span> 
                                    </a> 
                                </div>";

                        }

                        $currentPage++;
                        $pagesLeft--;

                    }

                    ?>
                    
                    <div class="pageNumberContainer">
                        <img src="assets/images/pageEnd.png" alt="">
                    </div>
                </div>
            </div>

        </div> 
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquer<y.fancybox.min.js"></script>
        <script src="https://unpkd.com/masonry-layout@4/dist/masonary.pkgd.min.js"></script>
        <script type="text/javascript" src="assets/js/script.js"></script>
    
    </body>  
    </html>