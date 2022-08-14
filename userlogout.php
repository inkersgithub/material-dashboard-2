<?php

include_once 's-shared.php';

if (isset($_SESSION['id'])) {
     session_destroy();
     unset($_SESSION['name']);
     unset($_SESSION['id']);
     unset($_SESSION['type']);
 }
 header("Location: userlogin.php");
