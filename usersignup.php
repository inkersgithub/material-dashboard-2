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
include_once 's-header.php'
?>

<body class="bg-gray-200">
  <div class="container position-sticky z-index-sticky top-0">
    <div class="row">
      <div class="col-12">
      </div>
    </div>
  </div>
  <main class="main-content  mt-0">
    <div class="page-header align-items-start min-vh-100" style="background-image: url('https://images.unsplash.com/photo-1497294815431-9365093b7331?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1950&q=80');">
      <span class="mask bg-gradient-dark opacity-6"></span>
      <div class="container my-auto">
        <div class="row">
          <div class="col-lg-8 col-md-10 col-12 mx-auto">
            <div class="card z-index-0 fadeIn3 fadeInBottom">
              <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                  <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Sign Up</h4>
                  <div class="row mt-3">
                    <p>Logo</p>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <form role="form" class="text-start" onsubmit="return CreateAccount()" id="signupForm">
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="input-group input-group-outline my-3">
                        <input type="text" required placeholder="Name" id="name" class="form-control">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="input-group input-group-outline my-3">
                        <input type="text" required placeholder="CSC ID" id="uid" class="form-control">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="input-group input-group-outline my-3">
                        <input type="text" required placeholder="Mobile No, 10 Digits" id="phone" class="form-control">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="input-group input-group-outline my-3">
                        <input type="text" required placeholder="Email" id="email" class="form-control">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="input-group input-group-outline my-3">
                        <input type="text" required placeholder="Address Line 1" id="addressLine1" class="form-control">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="input-group input-group-outline my-3">
                        <input type="text" required placeholder="Address Line 2" id="addressLine2" class="form-control">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="input-group input-group-outline my-3">
                        <input type="text" required placeholder="Address Line 3" id="addressLine3" class="form-control">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="input-group input-group-outline my-3">
                        <input type="text" required placeholder="Pincode" id="pincode" class="form-control">
                      </div>
                    </div>
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2">Sign Up</button>
                  </div>
                  <p style="text-align:center" id="message"></p>
                  <p style="text-align: center;">
                    <a style="font-weight: 500;" href="userlogin.php">
                      Login
                    </a>
                  </p>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer position-absolute bottom-2 py-2 w-100">
        <div class="container">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-12 col-md-12 my-auto">
              <div class="copyright text-center text-sm text-white text-lg-start" style="text-align: center !important;">
                © <script>
                  document.write(new Date().getFullYear())
                </script>,
                made with <i class="fa fa-heart" aria-hidden="true"></i> by
                <a href="https://www.creative-tim.com" class="font-weight-bold text-white" target="_blank">Inkers</a>
                Tech Labs
              </div>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </main>
  <script src="./s-shared.js"></script>
  <script>
    function CreateAccount() {

      ShowLoader(true);
      let name = document.getElementById("name");
      let uid = document.getElementById("uid");
      let phone = document.getElementById("phone");
      let email = document.getElementById("email");
      let addressLine1 = document.getElementById("addressLine1");
      let addressLine2 = document.getElementById("addressLine2");
      let addressLine3 = document.getElementById("addressLine3");
      let pincode = document.getElementById("pincode");

      var form_data = new FormData();
      form_data.append('name', name.value);
      form_data.append('uid', uid.value)
      form_data.append('phone', phone.value);
      form_data.append('email', email.value);
      form_data.append('addressLine1', addressLine1.value);
      form_data.append('addressLine2', addressLine2.value);
      form_data.append('addressLine3', addressLine3.value);
      form_data.append('pincode', pincode.value);
      form_data.append('requestType', 'createAccount')

      $.ajax({
        url: 'usercontroller.php',
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(result) {
          if (result) {
            console.log(result);
            if (result.status) {
              message.innerHTML = result.successMsg;
              document.getElementById("signupForm").reset();
              ShowLoader(false);
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
  </script>
  <!--   Core JS Files   -->
</body>

</html>