<?php

//////////////////////////////////////////////////
//Config Settings
//////////////////////////////////////////////////

//Environemnt - Production, Development
session_start();
$env = 'Productio';
date_default_timezone_set("Asia/Calcutta");
CustomConstructor();
include_once "s-mailservice.php";

//////////////////////////////////////////////////
//Config Settings
//////////////////////////////////////////////////

function CustomConstructor()
{
    ConnectToDatabase();
}

/////////////////////////////////////////////////
//Connect to Database
/////////////////////////////////////////////////
function ConnectToDatabase()
{
    global $env;
    global $con;

    if ($env == 'Production') {
        $con = mysqli_connect("localhost", "root", "Gamma@1234", "cscvlethrissur") or die("Error" . mysqli_error($con));
    } else {
        $con = mysqli_connect("localhost", "root", "root", "a") or die("Error" . mysqli_error($con));
    }
}

////////////////////////////////////////////
//Validation Functions Starts
////////////////////////////////////////////
function ValidateName($name)
{
    if (!preg_match("/^[a-zA-Z ]+$/", $name)) {
        return false;
    } else {
        return true;
    }
}

function ValidateMobileNumber($mobile)
{
    if (!preg_match('/^[0-9]{10}+$/', $mobile)) {
        return false;
    } else {
        return  true;
    }
}

function ValidatePincode($mobile)
{
    if (!preg_match('/^[0-9]{6}+$/', $mobile)) {
        return false;
    } else {
        return true;
    }
}

function ValidateEmail($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    } else {
        return true;
    }
}

////////////////////////////////////////////
//Utility Functions Starts
////////////////////////////////////////////
function GenerateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

