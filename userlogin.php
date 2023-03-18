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
include_once 's-header.php';
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
          <div class="col-lg-4 col-md-8 col-12 mx-auto">
            <div class="card z-index-0 fadeIn3 fadeInBottom">
              <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                  <img src="assets/bgImage.jpg" style="width: 101%;">
                  <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Sign in</h4>
                  <div class="row mt-3">
                  </div>
                </div>
              </div>
              <div class="card-body">
                <form id="loginForm" onsubmit="return Login()" autocomplete="do-not-autofill" role="form" class="text-start">
                  <div class="input-group input-group-outline my-3">
                    <input id="username" placeholder="Email/Mobile/CSC ID" autocomplete="do-not-autofill" type="text" class="form-control">
                  </div>
                  <div class="input-group input-group-outline mb-3">
                    <input id="password" placeholder="********" type="password" class="form-control">
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2">Sign in</button>
                  </div>
                </form>
              </div>
              <p id="message" style="text-align: center;"></p>
              <p style="text-align: center;">
                <a style="font-weight: 500;" href="usersignup.php">
                  Create new account
                </a>
              </p>
              <p style="text-align: center;">
                <a style="font-weight: 500;" href="forgetpassword.php">
                  Forget Password?
                </a>
              </p>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer position-absolute bottom-2 py-2 w-100">
        <div class="container">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-12 col-md-12 my-auto">
              <div class="copyright text-center text-sm text-white text-lg-start" style="text-align: center !important;">
                © KL-CSC-VLE SOCIETY THRISSUR <script>
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
  <!--   Core JS Files   -->
  <script src="./s-shared.js"></script>
  <script>
    function Login() {

      ShowLoader(true);
      let username = document.getElementById("username");
      let password = document.getElementById("password");

      var form_data = new FormData();
      form_data.append('username', username.value);
      form_data.append('password', password.value);
      form_data.append('requestType', 'login')

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
            ShowLoader(false);
            if (result.status) {
              if (result.type == 1) {
                window.location.href = 'links.php';
              } else {
                window.location.href = 'home.php';
              }
            } else {
              message.innerHTML = result.errorMsg;
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

  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/material-dashboard.min.js?v=3.0.0"></script>
</body>

</html>