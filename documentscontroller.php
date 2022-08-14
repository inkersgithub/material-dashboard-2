<?php

include_once "s-shared.php";

////////////////////////////////////////////////////////
//API Endpoints
////////////////////////////////////////////////////////

if (isset($_POST['requestType'])  && $_POST['requestType'] == 'addDocument') {

     $result = new \stdClass();
     $result->status = false;
     $result->errorMsg = "Some error occured";
     $isFeatured = $_POST['isFeatured'] == 'true' ? 1 : 0;

     $fileName = $_FILES['document']['name'];
     $fileSize = $_FILES['document']['size'];
     $fileTmp = $_FILES['document']['tmp_name'];
     $fileType = $_FILES['document']['type'];
     $fileNameExplode = explode('.', $fileName);
     $fileExt = strtolower(end($fileNameExplode));
     $tempFileName = rand(999, 9999) . time() . '.' . $fileExt;
     $tempFilePath = "uploads/" . $tempFileName;

     if (move_uploaded_file($fileTmp, $tempFilePath)) {
          $result = InsertNewDocument($_POST['title'], $tempFilePath, $isFeatured, $_SESSION['id']);
     }

     echo json_encode($result);
     exit();
}

if (isset($_POST['requestType'])  && $_POST['requestType'] == 'removeDocument') {
     echo json_encode(RemoveDocument($_POST['id'], $_SESSION['id']));
     exit();
}

if (isset($_POST['requestType'])  && $_POST['requestType'] == 'getDocuments') {
     $linkArray = array();
     $result = FetchDocuments($_POST['type']);
     while ($row = mysqli_fetch_object($result)) {
          $linkArray[] = $row;
     }
     $result->status = true;
     $result->linkList = $linkArray;
     echo json_encode($result);
     exit();
}
////////////////////////////////////////////////////////
//API Endpoints Ends
////////////////////////////////////////////////////////

////////////////////////////////////////////////////////
//Functional Methods
////////////////////////////////////////////////////////

//InsertNewDocument('Google', 'http://google.com', 1,1);
function InsertNewDocument($title, $path, $isFeatured, $userId)
{
     try {
          global $con;
          $result = new \stdClass();
          $result->status = false;
          $result->errorMsg = "Some error occured";
          $timeStamp = date("Y-m-d H:i:s");
          $path = mysqli_real_escape_string($con, $path);
          $title = mysqli_real_escape_string($con, $title);

          $insertQuery = "INSERT INTO `documents`(`title`, `path`, `is_featured`, `is_deleted`, `added_by`, `added_time`, `removed_by`, `removed_time`) 
          VALUES ('$title','$path','$isFeatured','0','$userId','$timeStamp','$userId','$timeStamp')";
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

//RemoveDocument(1,5);
function RemoveDocument($id, $userId)
{
     try {
          global $con;
          $result = new \stdClass();
          $result->status = false;
          $result->errorMsg = "Some error occured";
          $timeStamp = date("Y-m-d H:i:s");

          $updateQuery = "UPDATE `documents` SET `is_deleted`='1', `removed_by`='$userId', `removed_time`='$timeStamp' 
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
//FetchDocuments(1);
function FetchDocuments($type = 1, $itemCount = 150)
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

     $fetchSql = "SELECT * FROM `documents` $where ORDER BY id DESC LIMIT $itemCount";
     return mysqli_query($con, $fetchSql);
}

////////////////////////////////////////////////////////
//Functional Methods Ends
////////////////////////////////////////////////////////
