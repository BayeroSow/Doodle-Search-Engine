<?
class siteResultProvider {

    private $con; 
    public function __constructor($con) {

    }

    public function getNameResult($sterm) {
        
        $query = $this->con->prepare("SELECT *
                                        FROM sites where title LIKE :term
                                        OR  furl LIKE :term
                                        OR keywords LIKE :term
                                        OR description LIKE :term");
        
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
        
        $query = $this->con->prepare("SELECT COUNT * as total
                                        FROM sites where title LIKE  :term
                                        OR url LIKE :term
                                        OR keywords LIKE :term
                                        OR description LIKE :term
                                        ORDER BY clicks DESC
                                        LIMIT :fromLimit :pageSize");
        
        $searchTerm = "%". $term . "%";
        $query->bindParam(":term", $searchTerm);
        $query->bindParam(":fromLimit", $fromLimit, PDO::PARAM_INT);
        $query->bindParam(":pageSize", $pageSize, PDO::PARAM_INT);
        $squery->execute();


        $resutlsHtml = "<div class='siteResults'>";

        while($row = $squery ->fetch(PDO::FETCH_ASSOC)) {
            $id = $row["id"];
            $url = $row["url"];
            $title = $row["title"];
            $description = $row["description"]; 

            $title = $this->trimField($title, 55); 
            $description = $this->trimField($description, 230); 

            $resultsHtml .= "<div class='resultContainer'>
                                <h3 class='title'>
                                    <a class='result' href='$url' data-linkId='$id'>
                                        $title
                                    </a>
                                </h3>
                                <span class='url'>$url</span>
                                <span class='description'>description</span>
                
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



 