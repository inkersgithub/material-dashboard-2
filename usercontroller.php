<?php

include_once 's-shared.php';

////////////////////////////////////////////////////////
//API Endpoints
////////////////////////////////////////////////////////

if (isset($_POST['requestType'])  && $_POST['requestType'] == 'createAccount') {
     $result = CreateAccount($_POST['name'], $_POST['uid'], $_POST['email'], $_POST['phone'], $_POST['addressLine1'], $_POST['addressLine2'], $_POST['addressLine3'], $_POST['pincode']);
     echo json_encode($result);
     exit();
}


if (isset($_POST['requestType'])  && $_POST['requestType'] == 'login') {
     $result = Login($_POST['username'], $_POST['password']);
     echo json_encode($result);
     exit();
}

if (isset($_POST['requestType'])  && $_POST['requestType'] == 'approveRequest') {
     $result = ApproveUser($_POST['id'], $_POST['status'], $_SESSION['id']);
     echo json_encode($result);
     exit();
}

if (isset($_POST['requestType'])  && $_POST['requestType'] == 'getUsers') {
     $userArray = array();
     $result = FetchUsers($_POST['filter']);
     while ($row = mysqli_fetch_object($result)) {
          $userArray[] = $row;
     }
     $result->status = true;
     $result->userList = $userArray;
     echo json_encode($result);
     exit();
}

if (isset($_POST['requestType'])  && $_POST['requestType'] == 'getUsersApproved') {
     $userArray = array();
     $result = FetchUsers($_POST['filter'], 1);
     while ($row = mysqli_fetch_object($result)) {
          $userArray[] = $row;
     }
     $result->status = true;
     $result->userList = $userArray;
     echo json_encode($result);
     exit();
}

if (isset($_POST['requestType'])  && $_POST['requestType'] == 'changepassword') {
     $result = ChangePassword($_SESSION['id'], $_POST['npassword'], $_POST['password']);
     echo json_encode($result);
     exit();
}

if (isset($_POST['requestType'])  && $_POST['requestType'] == 'resetpassword') {
     $result = ResetPassword($_POST['username']);
     echo json_encode($result);
     exit();
}
////////////////////////////////////////////////////////
//API Endpoints Ends
////////////////////////////////////////////////////////

////////////////////////////////////////////////////////
//Functional Methods
////////////////////////////////////////////////////////
function ResetPassword($username)
{
     try {
          global $con;
          $result = new \stdClass();
          $result->status = false;
          $result->errorMsg = "Some error occured";
          $username = mysqli_real_escape_string($con, $username);

          $sql = "SELECT * FROM `users` WHERE 
            (`phone` = '$username' OR `email` = '$username' OR `uid` = '$username') 
             AND is_approved = 1 AND is_deleted = 0";
          $user = mysqli_query($con, $sql);
          $password = GenerateRandomString();

          if (mysqli_num_rows($user) > 0) {
               $userObj = mysqli_fetch_object($user);
               $updateQuery = "UPDATE `users` SET `password`='$password' WHERE (`phone` = '$username' OR `email` = '$username' OR `uid` = '$username') 
               AND is_approved = 1 AND is_deleted = 0";
               if (mysqli_query($con, $updateQuery)) {
                    $result->status = true;
                    SendEmail('Account approved', $userObj->email, TemporaryPassword($password));
                    $result->successMsg = "New password send through mail";
               }
          } else {
               $result->errorMsg = "No account found for this username";
          }
          return $result;
     } catch (Exception $ex) {
          $con->rollback();
     }
}

function ChangePassword($id, $npassword, $password)
{
     try {
          global $con;
          $result = new \stdClass();
          $result->status = false;
          $result->errorMsg = "Some error occured";
          $timeStamp = date("Y-m-d H:i:s");
          $npassword = mysqli_real_escape_string($con, $npassword);

          $checkSql = "SELECT id FROM `users` WHERE `id`=$id AND `password`='$password' AND is_approved = 1 AND is_deleted = 0";
          if (mysqli_num_rows(mysqli_query($con, $checkSql)) <= 0) {
               $result->status = false;
               $result->errorMsg = "Current password is incorrect";
               return $result;
          }

          $updateQuery = "UPDATE `users` SET `password`='$npassword' WHERE `id` = '$id'";
          if (!$con->query($updateQuery)) {
               $con->rollback();
               return $result;
               exit();
          }

          $con->commit();
          $result->status = true;
          $result->successMsg = "Approval status changed";
          return $result;
     } catch (Exception $ex) {
          $con->rollback();
     }
}

//Login('asd', 'xdWxCkm8aw');
function Login($username, $password)
{
     global $con;
     $result = new \stdClass();
     $result->status = false;
     $result->errorMsg = "Some error occured";

     $sql = "SELECT * FROM `users` WHERE 
            (`phone` = '$username' OR `email` = '$username' OR `uid` = '$username') 
            AND `password` = '$password' AND is_approved = 1 AND is_deleted = 0";
     $user = mysqli_query($con, $sql);

     if (mysqli_num_rows($user) > 0) {
          $userObj = mysqli_fetch_object($user);
          $result->name = $userObj->name;
          $result->email = $userObj->email;
          $result->phone = $userObj->phone;
          $result->id = $userObj->id;
          $result->type = $userObj->type;

          $_SESSION['name'] = $result->name;
          $_SESSION['id'] = $result->id;
          $_SESSION['type'] = $result->type;
          $_SESSION['phone'] = $result->phone;
          $_SESSION['uid'] = $userObj->uid;
          $result->status = true;
     } else {
          $result->errorMsg = "Invalid credentials";
     }
     return $result;
}

function ApproveUser($id, $status, $approvedBy = 1)
{
     try {
          global $con;
          $result = new \stdClass();
          $result->status = false;
          $result->errorMsg = "Some error occured";
          $timeStamp = date("Y-m-d H:i:s");

          $updateQuery = "UPDATE `users` SET `is_approved`='$status', `approved_by`='$approvedBy', `approved_time`='$timeStamp' 
                         WHERE `id` = '$id'";
          if (!$con->query($updateQuery)) {
               $con->rollback();
               return $result;
               exit();
          } else {
               $selectUser = "SELECT * FROM `users` WHERE `id`=$id";
               $fetchResult = mysqli_query($con, $selectUser);
               $userObj = mysqli_fetch_object($fetchResult);
               SendEmail('Account approved', $userObj->email, AccountApproved($userObj->password));
          }

          $con->commit();
          $result->status = true;
          $result->successMsg = "Password Changed";
          return $result;
     } catch (Exception $ex) {
          $con->rollback();
     }
}

//CreateAccount('name', 'uid1', 'email', 'phone2', 'address1', 'address2', 'address3', 'pincode');
function CreateAccount($name, $uid, $email, $phone, $address1, $address2, $address3, $pincode)
{
     try {
          global $con;
          $result = new \stdClass();
          $result->status = false;
          $result->errorMsg = "Some error occured";
          $timeStamp = date("Y-m-d H:i:s");
          $name = mysqli_real_escape_string($con, $name);
          $uid = mysqli_real_escape_string($con, $uid);
          $email = mysqli_real_escape_string($con, $email);
          $phone = mysqli_real_escape_string($con, $phone);
          $address1 = mysqli_real_escape_string($con, $address1);
          $address2 = mysqli_real_escape_string($con, $address2);
          $address3 = mysqli_real_escape_string($con, $address3);
          $pincode = mysqli_real_escape_string($con, $pincode);
          $password = GenerateRandomString();

          if (!ValidateMobileNumber($phone)) {
               $result->status = false;
               $result->errorMsg = "Invalid phone number";
               //echo $result->errorMsg;
               return $result;
          }

          if (!ValidateEmail($email)) {
               $result->status = false;
               $result->errorMsg = "Invalid email";
               //echo $result->errorMsg;
               return $result;
          }

          if (!ValidatePincode($pincode)) {
               $result->status = false;
               $result->errorMsg = "Invalid Pincode";
               //echo $result->errorMsg;
               return $result;
          }

          $checkUid = "SELECT id FROM `users` WHERE `uid` = '$uid' AND is_deleted = 0 AND is_approved = 1";
          if (mysqli_num_rows(mysqli_query($con, $checkUid))) {
               $result->status = false;
               $result->errorMsg = "Duplicate uid found";
               //echo $result->errorMsg;
               return $result;
          }

          $checkPhone = "SELECT id FROM `users` WHERE `phone` = '$phone' AND is_deleted = 0 AND is_approved = 1";
          if (mysqli_num_rows(mysqli_query($con, $checkPhone))) {
               $result->status = false;
               $result->errorMsg = "Duplicate phone number found";
               //echo $result->errorMsg;
               return $result;
          }

          $checkEmail = "SELECT id FROM `users` WHERE `email` = '$email' AND is_deleted = 0 AND is_approved = 1";
          if (mysqli_num_rows(mysqli_query($con, $checkEmail))) {
               $result->status = false;
               $result->errorMsg = "Duplicate email found";
               //echo $result->errorMsg;
               return $result;
          }

          $insertQuery = "INSERT INTO `users`( `uid`, `name`, `email`, `phone`, `password`, `address_line1`, `address_line2`, `address_line3`, `pincode`, `is_deleted`, `is_approved`, `approved_by`, `added_time`) 
          VALUES ('$uid','$name','$email','$phone','$password','$address1','$address2','$address3','$pincode','0','0','0','$timeStamp')";
          $result->query = $insertQuery;
          if (!$con->query($insertQuery)) {
               $con->rollback();
               return $result;
               exit();
          }

          $con->commit();
          $result->status = true;
          $result->successMsg = "Account created successfully, please wait until admin approve your account";
          return $result;
     } catch (Exception $ex) {
          $con->rollback();
     }
}

//FetchUsers();
function FetchUsers($filter = '', $approvalStatus = '')
{

     global $con;
     $result = new \stdClass();
     $result->status = false;
     $result->errorMsg = "Some error occured";
     $where = " WHERE  (`phone` LIKE  '%$filter%' OR  `email` LIKE '%$filter%' OR 
      `uid` LIKE '%$filter%' OR  `name` LIKE '%$filter%' OR  `pincode` LIKE '%$filter%') 
      AND is_deleted != 1  AND type = 0 AND is_approved LIKE '%$approvalStatus%'";
     $fetchSql = "SELECT * FROM `users` $where ORDER BY is_approved ASC, id DESC  LIMIT 150";
     return mysqli_query($con, $fetchSql);
}

////////////////////////////////////////////////////////
//Functional Methods Ends
////////////////////////////////////////////////////////