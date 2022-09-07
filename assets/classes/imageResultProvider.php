<?
class siteResultProvider {

    private $con; 
    public function __constructor($con) {

    }
    public function getNameResult($sterm) {
        
        $query = $this->con->prepare("SELECT *
                                        FROM Images
                                        WHERE (title LIKE :term
                                        OR alt LIKE :term)
                                        AND broken=0");
        
        $searchTerm = "%". $term . "%";
        $query->bindParam(":term", $term);
        $squery->exdecute();
        
        $row = $squery ->fetch(PDO::FETCH_ASSOC);
        return $row["total"];

                                  
    }
    public function getResultsHtml($page, $pageSize, $term) {

        $formLiit = ($page - 1) * $pagesize;
        //page1
        //page 1 : (1-1) * 20 = 0
        //page 2 : (2 - 1) * 20 = 20
        //page 3 : (3 - 1) * 20 = 40
        
        $query = $this->con->prepare("SELECT *
                                        FROM sites where title LIKE :term
                                        WHERE (title LIKE :term
                                        OR alt LIKE :term)
                                        AND broken=0
                                        ORDER BY clicks DESC
                                        LIMIT :fromLimit :pageSize");
        
        $searchTerm = "%". $term . "%";
        $query->bindParam(":term", $searchTerm);
        $query->bindParam(":fromLimit", $fromLimit, PDO::PARAM_INT);
        $query->bindParam(":pageSize", $pageSize, PDO::PARAM_INT);
        $squery->execute();


        $resutlsHtml = "<div class='imageResults'>";
        $count = 0;
        while($row = $squery ->fetch(PDO::FETCH_ASSOC)) {
            $count ++;
            $id = $row["id"];
            $imageUrl = $row["imageUrl"];
            $siteUrl  = $row["siteUrl"];
            $title = $row["title"];
            $alt = $row["alt"]; 

            if($title) {
                $displayText = $title;

            }
            else if ($alt) {
                $displayText = $alt;

            }
            else {
                $displayText = $imageUrl;

            }

            $resultsHtml .= "<div class='gridItem image$count'>
                                <a href='$imageUrl' data-fancybox data-caption='$displayText'
                                    data-siteurl='$siteUrl'>
                                    <script>
                                    ($document).ready(function(){
                                        loadImage(\"$imageUrl\", \"image$count\");

                                    });

                                    </script>
                                    
                                    
                                    <span class='details'>$displayText</span>   
                                </a> 

                            </div>";              

        }

        $resultsHtml .= "</div>";
        return $resultsHtml;

    }  

    private function trimField($string, $characterLimit) {
        $dots = strlen($string) > $characterLimit ? "...": "";
        return substr($string, 0, $characterLimit) . $dots;
    
    }


}
?>