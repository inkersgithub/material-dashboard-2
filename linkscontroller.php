<?php

include_once "s-shared.php";

////////////////////////////////////////////////////////
//API Endpoints
////////////////////////////////////////////////////////

if (isset($_POST['requestType'])  && $_POST['requestType'] == 'addLink') {
     $isFeatured = $_POST['isFeatured'] == 'true' ? 1 : 0;
     echo json_encode(InsertNewLink($_POST['title'], $_POST['link'], $isFeatured, $_SESSION['id']));
     exit();
}

if (isset($_POST['requestType'])  && $_POST['requestType'] == 'removeLink') {
     echo json_encode(RemoveLink($_POST['id'], $_SESSION['id']));
     exit();
}

if (isset($_POST['requestType'])  && $_POST['requestType'] == 'getLinks') {
     $linkArray = array();
     $result = FetchLinks($_POST['type']);
     while ($row = mysqli_fetch_object($result)) {
          $linkArray[] = $row;
     }
     $result->status = true;
     $result->linkList = $linkArray;
     echo json_encode($result);
     exit();
}
///////////////////////////
/////////////////////////////
//API Endpoints Ends
////////////////////////////////////////////////////////

////////////////////////////////////////////////////////
//Functional Methods
////////////////////////////////////////////////////////

//InsertNewLink('Google', 'http://google.com', 1,1);
function InsertNewLink($title, $link, $isFeatured, $userId)
{
     try {
          global $con;
          $result = new \stdClass();
          $result->status = false;
          $result->errorMsg = "Some error occured";
          $timeStamp = date("Y-m-d H:i:s");
          $link = mysqli_real_escape_string($con, $link);
          $title = mysqli_real_escape_string($con, $title);

          $insertQuery = "INSERT INTO `links`(`title`, `link`, `is_featured`, `is_deleted`, `added_by`, `added_time`, `removed_by`, `removed_time`) 
          VALUES ('$title','$link','$isFeatured','0','$userId','$timeStamp','$userId','$timeStamp')";
          if (!$con->query($insertQuery)) {
               $con->rollback();
               return $result;
               exit();
          }

          $con->commit();
          $result->status = true;
          $result->successMsg = "Successfully added";
          return $result;
     } catch (Exception $ex) {
          $con->rollback();
     }
}

//RemoveLink(1,5);
function RemoveLink($id, $userId)
{
     try {
          global $con;
          $result = new \stdClass();
          $result->status = false;
          $result->errorMsg = "Some error occured";
          $timeStamp = date("Y-m-d H:i:s");

          $updateQuery = "UPDATE `links` SET `is_deleted`='1', `removed_by`='$userId', `removed_time`='$timeStamp' 
                         WHERE `id` = '$id'";
          if (!$con->query($updateQuery)) {
               $con->rollback();
               return $result;
               exit();
          }

          $con->commit();
          $result->status = true;
          $result->successMsg = "Successfully removed";
          return $result;
     } catch (Exception $ex) {
          $con->rollback();
     }
}

//type = 1 - All
//type = 2 - Featured
//type = 3 - Normal
//FetchLinks(1);
function FetchLinks($type = 1, $itemCount = 150)
{
     global $con;
     $result = new \stdClass();
     $result->status = false;
     $result->errorMsg = "Some error occured";
     $where = " WHERE is_deleted != 1 ";

     if ($type == 2) {
          $where = $where . " AND is_featured = 1 ";
     } elseif ($type == 3) {
          $where = $where . " AND is_featured = 0 ";
     }

     $fetchSql = "SELECT * FROM `links` $where ORDER BY id DESC LIMIT $itemCount";
     return mysqli_query($con, $fetchSql);
}

////////////////////////////////////////////////////////
//Functional Methods Ends
////////////////////////////////////////////////////////
