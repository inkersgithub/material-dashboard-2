<!--
=========================================================
* Material Dashboard 2 - v3.0.0
=========================================================

* Product Page: https://www.creative-tim.com/product/material-dashboard
* Copyright 2021 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<?php
include_once 's-shared.php';
if (!isset($_SESSION['id']) || $_SESSION['id'] == null || $_SESSION['id'] == "" || $_SESSION['type'] != 1) {
     header("Location: userlogin.php");
     exit();
}
include_once 's-header.php'
?>

<body class="g-sidenav-show  bg-gray-200">
     <!--   SideNavigation   -->
     <?php
     include_once 's-sidenavigation.php';
     ?>
     <!--   SideNavigation   -->
     <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
          <div class="container-fluid py-4" style="padding-top:0% !important;margin-top:0%">
               <div class="card mt-4" id="password">
                    <form onsubmit="return LoadUsers()">
                         <div class="card-header" style="padding-bottom: 0%">
                              <h5 style="display: inline;">BILL USERS</h5>
                              <button type="submit" style="float: right;margin-top: -1%;" class="btn bg-gradient-primary">
                                   Filter
                              </button>
                              <input type="text" style="float: right;margin-right: 1%;margin-top: -.5%;" placeholder="Filter" id="filter" class="" onfocus="focused(this)" onfocusout="defocused(this)">
                         </div>
                    </form>
               </div>

               <div class="row my-4">
                    <div class="col-12">
                         <div class="card" style="max-height: 80vh;">
                              <div class="table-responsive">
                                   <table class="table align-items-center mb-0">
                                        <thead>
                                             <tr>
                                                  <th style="font-size: 14px !important;" class="text-uppercase text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                                                  <th style="font-size: 14px !important;" class="text-uppercase text-xxs font-weight-bolder opacity-7 ps-2">Name</th>
                                                  <th style="font-size: 14px !important;" class="text-uppercase text-xxs font-weight-bolder opacity-7 ps-2">CSC ID</th>
                                                  <th style="font-size: 14px !important;" class="text-uppercase text-xxs font-weight-bolder opacity-7 ps-2">Phone, Email</th>
                                                  <th style="font-size: 14px !important;" class="text-uppercase text-xxs font-weight-bolder opacity-7 ps-2">Address</th>
                                                  <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7" style="text-align:right;width:15%">
                                                  </th>
                                             </tr>
                                        </thead>
                                        <tbody id="linkTableBody">
                                        </tbody>
                                   </table>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </main>

     <!-- Modal -->
     <div class="modal fade" id="billUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
               <div class="modal-content">
                    <div class="modal-header">
                         <h5 style="font-weight: 500 !important;margin-left: 2%;" class="modal-title font-weight-normal" id="exampleModalLabel">
                              BILL USER
                         </h5>
                         <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                         </button>
                    </div>
                    <div class="modal-body">
                         <div class="card-body pt-0">
                              <form onsubmit="return AddItem()" id="linkForm">
                                   <div class="row">
                                        <div class="col-sm-7">
                                             <div class="input-group input-group-outline">
                                                  <label class="form-label">Item Name</label>
                                                  <input type="text" autofocus required id="itemName" class="form-control">
                                             </div>
                                        </div>
                                        <div class="col-sm-3">
                                             <div class="input-group input-group-outline">
                                                  <label class="form-label">Amount</label>
                                                  <input step="0.01" type="number" required id="itemAmount" class="form-control" onfocus="focused(this)" onfocusout="defocused(this)">
                                             </div>
                                        </div>
                                        <div class="col-sm-2">
                                             <div class="input-group input-group-outline">
                                                  <label class="form-label">Title</label>
                                                  <button type="submit" id="itemAdd" class="btn bg-gradient-primary">Add</button>
                                             </div>
                                        </div>
                                   </div>
                              </form>
                              <table class="table align-items-center mb-0">
                                   <thead>
                                        <tr>
                                             <th style="font-size: 14px !important;border:black" class="text-uppercase text-xxs font-weight-bolder opacity-7 ps-2">Item</th>
                                             <th style="font-size: 14px !important;float:right" class="text-uppercase text-xxs font-weight-bolder opacity-7 ps-2">Amount</th>
                                        </tr>
                                   </thead>
                                   <tbody id="addItemBody">
                                   </tbody>
                              </table>
                         </div>
                    </div>
                    <div class="modal-footer">
                         <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                         <button type="button" onclick="AddBillConfirm()" class="btn bg-gradient-primary">Save changes</button>
                    </div>
               </div>
          </div>
     </div>
     <script src="./s-shared.js"></script>
     <script>
          LoadUsers();
          var billItems = [];
          var userId = 0;

          function AddItem() {
               let tempObj = {
                    item: document.getElementById('itemName').value,
                    amount: document.getElementById('itemAmount').value,
               }
               billItems.push(tempObj);
               LoadItemData();
               document.getElementById('linkForm').reset();
               document.getElementById("itemName").focus();
               return false;
          }

          function AddBillConfirm() {
               ShowLoader(true);
               if (billItems.length <= 0) {
                    alert("Please add atleast one item");
                    ShowLoader(false);
                    return;
               }

               //if (confirm("Please confirm?")) {
               $.post("billcontroller.php", {
                         id: userId,
                         items: billItems,
                         requestType: 'addBills'
                    },
                    function(data) {
                         if (data) {
                              console.log(data)
                              let result = JSON.parse(data);
                              window.open('billreport.php?groupid=' + result.group + '&userid=' + userId, "_blank");
                              billItems = [];
                              LoadItemData();
                         }
                         ShowLoader(false);
                    }
               );
               //}
               return false;
          }

          function LoadItemData() {
               let tableData = '';
               var totalAmount = 0;
               billItems.forEach(obj => {
                    tableData = tableData + '<tr><td><p class="teFxt-sm font-weight-normal mb-0">' + obj.item + '</p></td><td><p class="teFxt-sm font-weight-normal mb-0" style="float:right">' + parseFloat(obj.amount).toFixed(2) + '</p></td></tr>';
                    totalAmount = Number(obj.amount) + Number(totalAmount);
               });
               tableData = tableData + '<tr><td><p class="teFxt-sm font-weight-normal mb-0">Total</p></td><td><p class="teFxt-sm font-weight-normal mb-0" style="float:right">' + parseFloat(totalAmount).toFixed(2) + '</p></td></tr>';
               document.getElementById('addItemBody').innerHTML = tableData;
          }

          function BillUser(user) {
               userId = user;
               billItems = [];
               LoadItemData();
               $('#billUserModal').modal('show');
          }

          function LoadUsers() {
               ShowLoader(true);
               $.post("usercontroller.php", {
                         type: 1,
                         filter: document.getElementById('filter').value,
                         requestType: 'getUsersApproved'
                    },
                    function(data) {
                         if (data) {
                              let result = JSON.parse(data);
                              LoadTable(result.userList);
                         }
                    }
               );
               return false;
          }

          function LoadTable(linkList) {
               let tableData = '';
               linkList.forEach(obj => {

                    let approval = '';
                    if (obj.is_approved == 1) {
                         approval = '<p style="color:green"><span style="cursor: pointer;"class="badge bg-gradient-success" onclick="BillUser(' + obj.id + ')">Bill User</span></p>';
                    }

                    let backgroundColorstyle = obj.is_featured == 1 ? 'background-color: antiquewhite;' : 'background-color: white;';
                    tableData = tableData + '<tr style="' + backgroundColorstyle + '"><td><p class="teFxt-sm font-weight-normal mb-0">' + new Date(obj.added_time).toLocaleDateString() + '</p></td><td><p class="text-sm font-weight-normal mb-0">' + obj.name + '</p></td><td><p class="text-sm font-weight-normal mb-0">' + obj.uid + '</p></td><td><p class="text-sm font-weight-normal mb-0">' + obj.phone + '<br>' + obj.email + '</p></td><td><p class="text-sm font-weight-normal mb-0">' + obj.address_line1 + ',<br> ' + obj.address_line2 + ',<br> ' + obj.address_line3 + ', ' + obj.pincode + '</p></td><td id="approvalStatus' + obj.id + '">' + approval + '</td></tr>';
               });
               document.getElementById('linkTableBody').innerHTML = tableData;
               ShowLoader(false);
          }
     </script>
     <!--   Core JS Files   -->
     <script src="./assets/js/core/bootstrap.min.js"></script>
     <script src="./assets/js/plugins/perfect-scrollbar.min.js"></script>
     <script src="./assets/js/plugins/smooth-scrollbar.min.js"></script>
     <!-- Github buttons -->
     <script async defer src="https://buttons.github.io/buttons.js"></script>
     <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
     <script src="./assets/js/material-dashboard.min.js?v=3.0.0"></script>
</body>

</html>