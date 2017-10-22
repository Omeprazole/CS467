<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
    include 'sqlInfo.php';

    // Start XML file, create parent node
    $dom = new DOMDocument("1.0");
    $node = $dom->createElement("markers");
    $parnode = $dom->appendChild($node);

    //connect to database
    $mysqli = new mysqli($dbhost, $dbname, $dbpass, $dbuser);

    //check connection
    if(!$mysqli || $mysqli->connect_errno) {
        die("connection error" .$mysqli->connect_errno ."".$mysqli->connect_error);
    }

    //Get type from input
    $type = $_GET["type"];

    //Fetch data from database
    $query = "SELECT main.id, main.type, main.description, main.image,
                s1.name, s1.lat, s1.lng, s2.name AS last_name,
                s2.lat AS last_lat, s2.lng AS last_lng, s1.hyperlink
              FROM main
              LEFT JOIN stops AS s1 ON main.stop_id = s1.id
              LEFT JOIN stops AS s2 ON main.last_stop_id = s2.id
              WHERE type = $type";

    header("Content-type: text/xml");

    if ($result = $mysqli->query($query)) {
        //output data of each row
       if ($result->num_rows > 0) {
           while($row = $result->fetch_assoc()) {
              $node = $dom->createElement("marker");
              $newnode = $parnode->appendChild($node);
              $newnode->setAttribute("id",$row['id']);
              $newnode->setAttribute("type",$row['type']);
              $newnode->setAttribute("name", $row['name']);
              $newnode->setAttribute("lat", $row['lat']);
              $newnode->setAttribute("lng", $row['lng']);
              $newnode->setAttribute("last_name", $row['last_name']);
              $newnode->setAttribute("last_lat", $row['last_lat']);
              $newnode->setAttribute("last_lng", $row['last_lng']);
              $newnode->setAttribute("hyperlink", $row['hyperlink']);
              $newnode->setAttribute("description", utf8_encode($row['description']));
              $newnode->setAttribute("image", $row['image']);
           }

        } else {
            echo "0 results";
        }
      $mysqli->close();
    } else {
      die("fetch data fail");
    }

    echo $dom->saveXML();
?>
