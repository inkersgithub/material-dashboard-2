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
                    <div class="card-header" style="padding-bottom: 0%">
                         <h5 style="display: inline;">USERS</h5>
                         <button type="button" style="float: right;margin-top: -1%;" class="btn bg-gradient-primary" onclick="LoadUsers()">
                              Filter
                         </button>
                         <input type="text" style="float: right;margin-right: 1%;margin-top: -.5%;" placeholder="Filter" id="filter" required class="" onfocus="focused(this)" onfocusout="defocused(this)">
                    </div>
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
                                                  <th style="font-size: 14px !important;" class="text-uppercase text-xxs font-weight-bolder opacity-7 ps-2">Phone, Eamil</th>
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
     <div class="modal fade" id="addLinkModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
               <div class="modal-content">
                    <form onsubmit="return addNewLink()" id="linkForm">
                         <div class="modal-header">
                              <h5 style="font-weight: 500 !important;margin-left: 2%;" class="modal-title font-weight-normal" id="exampleModalLabel">
                                   ADD LINKS
                              </h5>
                              <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                              </button>
                         </div>
                         <div class="modal-body">
                              <div class="card-body pt-0">
                                   <div class="input-group input-group-outline">
                                        <label class="form-label">Title</label>
                                        <input type="text" onclick="ResetErrorMessage()" required id="title" class="form-control" onfocus="focused(this)" onfocusout="defocused(this)">
                                   </div>
                                   <div class="input-group input-group-outline my-4">
                                        <label class="form-label"></label>
                                        <br>
                                        <textarea onclick="ResetErrorMessage()" placeholder="Link" id="link" required style="height: 120px;" class="form-control" onfocus="focused(this)" onfocusout="defocused(this)"></textarea>
                                   </div>
                                   <div>
                                        <ul class="list-group">
                                             <li class="list-group-item border-0 px-0">
                                                  <div class="form-check form-switch ps-0">
                                                       <input class="form-check-input ms-auto" type="checkbox" id="isFeatured">
                                                       <label class="form-check-label text-body ms-3 text-truncate w-80 mb-0" for="isFeatured">Featured</label>
                                                  </div>
                                             </li>
                                        </ul>
                                   </div>
                                   <p id="message"></p>
                              </div>
                         </div>
                         <div class="modal-footer">
                              <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                              <button type="submit" class="btn bg-gradient-primary">Save changes</button>
                         </div>
                    </form>
               </div>
          </div>
     </div>
     <script src="./s-shared.js"></script>
     <script>
          LoadUsers();

          function ResetErrorMessage() {
               document.getElementById("message").innerHTML = "";
          }

          function LoadUsers() {
               ShowLoader(true);
               $.post("usercontroller.php", {
                         type: 1,
                         filter: document.getElementById('filter').value,
                         requestType: 'getUsers'
                    },
                    function(data) {
                         if (data) {
                              let result = JSON.parse(data);
                              LoadTable(result.userList);
                         }
                    }
               );
          }

          function LoadTable(linkList) {
               let tableData = '';
               linkList.forEach(obj => {

                    if (obj.is_approved == 1) {
                         approval = '<p style="color:green">Approved</p>';
                    } else if (obj.is_approved == 2) {
                         approval = '<p style="color:red">Declined</p>';
                    } else {
                         approval = '<p style=""><span style="cursor: pointer;"class="badge bg-gradient-success" onclick="Approve(' + obj.id + ',true)">Approve</span><span class="badge bg-gradient-danger" style="cursor: pointer;" onclick="Approve(' + obj.id + ',false)">Decline</span></p>';
                    }

                    let backgroundColorstyle = obj.is_featured == 1 ? 'background-color: antiquewhite;' : 'background-color: white;';
                    tableData = tableData + '<tr style="' + backgroundColorstyle + '"><td><p class="teFxt-sm font-weight-normal mb-0">' + new Date(obj.added_time).toLocaleDateString() + '</p></td><td><p class="text-sm font-weight-normal mb-0">' + obj.name + '</p></td><td><p class="text-sm font-weight-normal mb-0">' + obj.uid + '</p></td><td><p class="text-sm font-weight-normal mb-0">' + obj.phone + '<br>'+obj.email+'</p></td><td><p class="text-sm font-weight-normal mb-0">' + obj.address_line1 + ',<br> ' + obj.address_line2 + ',<br> ' + obj.address_line3 + ', ' + obj.pincode + '</p></td><td id="approvalStatus' + obj.id + '">' + approval + '</td></tr>';
               });
               document.getElementById('linkTableBody').innerHTML = tableData;
               ShowLoader(false);
          }

          function Approve(id, status) {
               if (status) {
                    if (confirm("Are you sure you want to approve this user?")) {
                         ShowLoader(true);
                         $.post("usercontroller.php", {
                                   id: id,
                                   status: 1,
                                   requestType: 'approveRequest'
                              },
                              function(data) {
                                   if (data) {
                                        let result = JSON.parse(data);
                                        LoadUsers();
                                   }
                              }
                         );
                    }
               } else {
                    if (confirm("Are you sure you want to decline this user?")) {
                         ShowLoader(true);
                         $.post("usercontroller.php", {
                                   id: id,
                                   status: 2,
                                   requestType: 'approveRequest'
                              },
                              function(data) {
                                   if (data) {
                                        let result = JSON.parse(data);
                                        LoadUsers();
                                   }
                              }
                         );
                    }
               }
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