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
include_once 's-header.php';
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
          <h5 style="display: inline;">LINKS</h5>
          <button type="button" style="float: right;margin-top: -1%;" class="btn bg-gradient-primary" data-bs-toggle="modal" onclick="ResetErrorMessage()" data-bs-target="#addLinkModal">
            Add Link
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
                    <th style="font-size: 14px !important;" class="text-uppercase text-xxs font-weight-bolder opacity-7 ps-2">Link</th>
                    <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7" style="text-align:right"></th>
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
    LoadLinks();

    function addNewLink() {

      ShowLoader(true);
      let title = document.getElementById("title");
      let link = document.getElementById("link");
      let isFeatured = document.getElementById("isFeatured");
      let message = document.getElementById("message");

      $.post("linkscontroller.php", {
          type: 1,
          requestType: 'addLink',
          title: title.value,
          link: link.value,
          isFeatured: isFeatured.checked
        },
        function(data) {
          if (data) {
            let result = JSON.parse(data);
            if (result.status) {
              LoadLinks();
              message.innerHTML = result.successMsg;
              document.getElementById("linkForm").reset();
            } else {
              message.innerHTML = result.errorMsg;
              ShowLoader(false);
            }
          }
        }
      );
      return false;
    }

    function ResetErrorMessage() {
      document.getElementById("message").innerHTML = "";
    }

    function LoadLinks() {
      ShowLoader(true);
      $.post("linkscontroller.php", {
          type: 1,
          requestType: 'getLinks'
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
        tableData = tableData + '<tr style="' + backgroundColorstyle + '"><td><p class="teFxt-sm font-weight-normal mb-0">' + new Date(obj.added_time).toLocaleDateString() + '</p></td><td><p class="text-sm font-weight-normal mb-0">' + obj.title + '</p></td><td><p class="text-sm font-weight-normal mb-0"><a href="' + obj.link + '" target="_blank" style="color: blue">' + obj.link + '</a></p></td><td><p class="text-sm font-weight-normal mb-0" style="float: right;margin-right:20px;color:red"><span style="cursor: pointer;" onclick="RemoveLinks(' + obj.id + ')">Remove</span></p></td></tr>';
      });
      document.getElementById('linkTableBody').innerHTML = tableData;
      ShowLoader(false);
    }

    function RemoveLinks(id) {
      if (confirm("Are you sure you want to remove this link?")) {
        ShowLoader(true);
        $.post("linkscontroller.php", {
            id: id,
            requestType: 'removeLink'
          },
          function(data) {
            if (data) {
              let result = JSON.parse(data);
              LoadLinks();
            }
          }
        );
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