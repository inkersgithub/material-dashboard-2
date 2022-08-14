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
          <h5 style="display: inline;">DOCUMENTS</h5>
          <button type="button" style="float: right;margin-top: -1%;" class="btn bg-gradient-primary" data-bs-toggle="modal" data-bs-target="#addLinkModal">
            Add Document
          </button>
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
                    <th style="font-size: 14px !important;" class="text-uppercase text-xxs font-weight-bolder opacity-7 ps-2">Title</th>
                    <th style="font-size: 14px !important;" class="text-uppercase text-xxs font-weight-bolder opacity-7 ps-2"></th>
                    <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7" style="text-align:right"></th>
                  </tr>
                </thead>
                <tbody id="documentTableBody">
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
        <form onsubmit="return addNewDocument()" id="documentForm">
          <div class="modal-header">
            <h5 style="font-weight: 500 !important;margin-left: 2%;" class="modal-title font-weight-normal" id="exampleModalLabel">
              ADD DOCUMENT
            </h5>
            <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="card-body pt-0">
              <div class="input-group input-group-outline">
                <label class="form-label">Title</label>
                <input type="text" required id="title" class="form-control" onfocus="focused(this)" onfocusout="defocused(this)">
              </div>
              <div class="input-group input-group-outline my-4">
                <input type="file" required id="document" class="form-control" onfocus="focused(this)" onfocusout="defocused(this)">
              </div>
              <div>
                <ul class="list-group">
                  <li class="list-group-item border-0 px-0">
                    <div class="form-check form-switch ps-0">
                      <input id="isFeatured" class="form-check-input ms-auto" type="checkbox" id="flexSwitchCheckDefault3">
                      <label class="form-check-label text-body ms-3 text-truncate w-80 mb-0" for="flexSwitchCheckDefault3">Featured</label>
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
    LoadDocuments();

    function addNewDocument() {

      ShowLoader(true);
      let title = document.getElementById("title");
      // let document = document.getElementById("document");
      let isFeatured = document.getElementById("isFeatured");
      let message = document.getElementById("message");


      var file_data = $('#document').prop('files')[0];
      var form_data = new FormData();
      form_data.append('document', file_data);
      form_data.append('title', title.value)
      form_data.append('isFeatured', isFeatured.checked)
      form_data.append('requestType', 'addDocument')

      $.ajax({
        url: 'documentscontroller.php', // <-- point to server-side PHP script 
        dataType: 'json', // <-- what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(result) {
          if (result) {
            console.log(result);
            if (result.status) {
              LoadDocuments();
              message.innerHTML = result.successMsg;
              document.getElementById("documentForm").reset();
            } else {
              message.innerHTML = result.errorMsg;
              ShowLoader(false);
            }
          }
        },
        error: function(result) {
          console.log(result);
        }
      });
      return false;
    }

    function ResetErrorMessage() {
      document.getElementById("message").innerHTML = "";
    }

    function LoadDocuments() {
      ShowLoader(true);
      $.post("documentscontroller.php", {
          type: 1,
          requestType: 'getDocuments'
        },
        function(data) {
          if (data) {
            let result = JSON.parse(data);
            LoadTable(result.linkList);
          }
        }
      );
    }

    function LoadTable(linkList) {
      let tableData = '';
      linkList.forEach(obj => {

        let backgroundColorstyle = obj.is_featured == 1 ? 'background-color: antiquewhite;' : 'background-color: white;';
        tableData = tableData + '<tr style="' + backgroundColorstyle + '"><td><p class="teFxt-sm font-weight-normal mb-0">' + new Date(obj.added_time).toLocaleDateString() + '</p></td><td><p class="text-sm font-weight-normal mb-0">' + obj.title + '</p></td><td><p class="text-sm font-weight-normal mb-0" style="color:blue"><span style="cursor: pointer;" onclick=DownloadDocument("' + obj.path + '")>Download</span></p></td><td><p class="text-sm font-weight-normal mb-0" style="float: right;margin-right:20px;color:red"><span style="cursor: pointer;" onclick="RemoveDocument(' + obj.id + ')">Remove</span></p></td></tr>';
      });
      document.getElementById('documentTableBody').innerHTML = tableData;
      ShowLoader(false);
    }

    function DownloadDocument(path) {
      window.open(path, "_blank");
    }

    function RemoveDocument(id) {
      if (confirm("Are you sure you want to remove this document?")) {
        ShowLoader(true);
        $.post("documentscontroller.php", {
            id: id,
            requestType: 'removeDocument'
          },
          function(data) {
            if (data) {
              let result = JSON.parse(data);
              LoadDocuments();
            }
          }
        );
      }
    }
  </script>
  <!--   Core JS Files   -->
  <script src="./assets/js/core/popper.min.js"></script>
  <script src="./assets/js/core/bootstrap.min.js"></script>
  <script src="./assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="./assets/js/plugins/smooth-scrollbar.min.js"></script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="./assets/js/material-dashboard.min.js?v=3.0.0"></script>
</body>

</html>