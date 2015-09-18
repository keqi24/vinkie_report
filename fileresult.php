<?php
//    if ($_FILES["sale"]["error"] > 0)
//    {
//      echo "Error: " . $_FILES["file"]["error"] . "<br />";
//      exit();
//    }
//    
//    
//      echo "isset:".empty($_FILES["sale"]["name"])."<br />";
//      echo "Upload: " . $_FILES["file"]["name"] . "<br />";
//      echo "Type: " . $_FILES["file"]["type"] . "<br />";
//      echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
//      echo "Stored in: " . $_FILES["file"]["tmp_name"];
      
    $resultData = array();
    $resultD = array();
    
    $fileNames = array("other","research","sale" );
    
    foreach ($fileNames as $fileName) {
        if($_FILES[$fileName]["error"] > 0) {
            echo "You don't upload ".$fileName." file!<br/>";
            exit();
        }
        
        if(empty($_FILES[$fileName]["name"])) {
            echo "Some problems happen to ".$fileName.", retry again！<br />";
            exit();
        }
    }
    
   
    $resultP = array();
    foreach($fileNames as $name) {
        $resultP[$name] = array();
        dealOneFile($_FILES[$name], $resultData, $resultD, $resultP[$name]);
        asort($resultP[$name]);
    }
    asort($resultD);
//    echo "resultP:";
//    print_r($resultP);
//    echo "<br />";
//    echo "resultD:";
//    print_r($resultD);
//    echo "<br />";
//    echo 'result';
//    print_r($saleResult);

Header("Content-type: application/octet-stream"); 
Header("Accept-Ranges: bytes"); 
//Header("Accept-Length:".  strlen($returnFile)); 
Header("Content-Disposition: attachment; filename=result.txt"); 
    echo "name";
    foreach($resultD as $date) {
        echo "\t".$date;
    }
    echo "\r\n";
    foreach ($fileNames as $fileName) {
        echo $fileName."\r\n";
        foreach($resultP[$fileName] as $name) {
            echo $name;
            foreach($resultD as $date) {
                 if(isset($resultData[$name][$date])) {
                     echo "\t1";
                 } else {
                     echo "\t0";
                 }
            }
            echo "\r\n";
           
        }
    }
   
    
    function dealOneFile($file, &$result, &$resultDate, &$resultPerson) {
        $tmpFileName = $file["tmp_name"];
        $tmpFile = fopen($tmpFileName, "r") or die("Unable to open file");

        while(!feof($tmpFile)) {
          $lineData = dealOneLine(fgets($tmpFile));
          if(!in_array($lineData["date"], $resultDate)) {
              array_push($resultDate, $lineData["date"]);
          }
          if(!in_array($lineData["name"], $resultPerson)) {
              array_push($resultPerson, $lineData["name"]);
          }
         
          if(!isset($result[$lineData["name"]])) {
              $result[$lineData["name"]] = array();
          } 
          $tmpDateArray = &$result[$lineData["name"]];
          if(!isset($tmpDateArray[$lineData["date"]])) {
              $tmpDateArray[$lineData["date"]] = 1;
          }
        }

        fclose($tmpFile);
    }
      
    function dealOneLine($line) {
        $lineArray = explode(" ", $line);
        $date = $lineArray[0];
        $nameArray = explode(",", $lineArray[4]);
        $name = $lineArray[3]." ".$nameArray[0];
        if(strpos($lineArray[3], ",") > 0) {
            $tmpArray = explode(",", $lineArray[3]);
            $name = $tmpArray[0];
        }
        return array("date"=>$date, "name"=>$name);
    }


