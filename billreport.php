<?php
//reference the Dompdf namespace
require_once 'dompdf/autoload.inc.php';
include_once 's-shared.php';
require_once 'logo.php';

use Dompdf\Dompdf;
//initialize dompdf class
$document = new Dompdf();



$headerTitle = "Header";
$date = date('d/M/Y', strtotime(date("Y-m-d H:i:s")));
$tableHead = '<tr>
                    <th class="tableRow">Sl.No</th>
                    <th class="tableRow" style="width:80%">Item Name</th>
                    <th class="tableRow"style="float:right">Amount</th>
               </tr>';
$footer = "";
$group = $_GET['groupid'];
$tableBody = "";
$fetchSql = "SELECT * FROM `bills` WHERE group_id='$group'";
$fetchResult = mysqli_query($con, $fetchSql);
$i = 1;
$total = 0;
$userId = $_GET['userid'];

$userFetchQuery = "SELECT * FROM  `users` WHERE id=" . $userId;
$userFetchResult = mysqli_query($con, $userFetchQuery);
$userObj = mysqli_fetch_object($userFetchResult);

while ($row = mysqli_fetch_array($fetchResult)) {
   $tableBody = $tableBody . '<tr>
                <td class="tableRow" style="text-align:center">' . $i . '</td>
                <td class="tableRow" style="text-align:left;width:80%">' . $row['item'] . '</td>
                <td class="tableRow" style="text-align:right">' . $row['amount'] . '</td>
             </tr>';
   $i++;
   $total = $total + $row['amount'];
}

$tableBody = $tableBody . '<tr>
                <td class="tableRow" style="text-align:center"></td>
                <td class="tableRow" style="text-align:left;width:80%">Total</td>
                <td class="tableRow" style="text-align:right">' . $total . '</td>
             </tr>';


$html = '<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>KL-CSC-VLE-SOCIETY</title>
<link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
<style type="text/css">
   table {
      page-break-inside: auto;
         width: 100%;
         padding: 1.5%;
  }

  tr {
      page-break-inside: avoid;
      page-break-after: auto
  }


  tfoot {
      display: table-footer-group
  }

  th {
      border: 1px solid #dddddd;
      text-align: left;
      padding: 5px;
         text-align :center;
      font-size: 14px;
  }
  td{
         padding: 8px;
  }
  tr{
      font-size: 13px;
  }
  tr:nth-child(even) {
      background-color: #dddddd;
  }
    .footer {
      position: fixed;
      left: 0;
      bottom: 0;
      width: 100%;
      border-top: solid 1px black;
  }
</style>
</head>
<body data-new-gr-c-s-check-loaded="14.997.0" data-gr-ext-installed="">
 <h3 style="text-align:center;margin:4px">' . $headerTitle . '</h3>
 <h4 style="text-align:center;margin:4px">' . $userObj->name . ', '.$userObj->uid.'</h4>
 <h4 style="text-align:center;margin:4px">' . $userObj->address_line1 . ', '.$userObj->address_line2.', '.$userObj->address_line3.', '.$userObj->pincode.'</h4>
<table class="tableConfirm" style="margin-top:1%">
   <thead>
      ' . $tableHead . '
   </thead>
   <tbody id="viewVoucherTableBody">
      ' . $tableBody . '
   </tbody>
</table>
 ' . $footer . '
</body>
</html>
';

//set page size and orientation
//Render the HTML as PDF
//Get output of generated pdf in Browser
//1  = Download
//0 = Preview

$document->loadHtml($html);
$document->setPaper('A4', 'potrait');
$document->render();
$document->stream("Webslesson", array("Attachment" => 0));
//echo $html;