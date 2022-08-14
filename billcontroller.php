<?php

include_once "s-shared.php";

if (isset($_POST['requestType'])  && $_POST['requestType'] == 'addBills') {
     $result = AddBill($_POST['id'], $_POST['items'], $_SESSION['id']);
     echo json_encode($result);
     exit();
}

function AddBill($userId, $items, $adminId)
{
     try {
          global $con;
          $result = new \stdClass();
          $result->status = false;
          $result->errorMsg = "Some error occured";
          $timeStamp = date("Y-m-d H:i:s");

          $sqlGroupCheck = "SELECT `group_id` FROM `bills` ORDER BY `id` DESC";
          $groupFetch = mysqli_fetch_object(mysqli_query($con, $sqlGroupCheck));
          $group = $groupFetch->group_id + 1;

          foreach ($items as $value) {

               $itemName = $value['item'];
               $itemAmount = $value['amount'];
               $sqlMulti = "INSERT INTO `bills`(`group_id`, `user_id`, `item`, `amount`, `is_deleted`, `added_time`, `added_by`) VALUES ('$group','$userId','$itemName','$itemAmount','0','$timeStamp','$adminId')";

               if (!$con->query($sqlMulti)) {
                    $con->rollback();
                    return $result;
                    exit();
               }
          }

          $con->commit();
          $result->group = $group;
          $result->status = true;
          $result->successMsg = "Approval status changed";
          return $result;
     } catch (Exception $ex) {
          $con->rollback();
     }
}
