<?php


include("config.php");
session_set_cookie_params($config['session_time']); 
session_name($config['session_name']);
session_start();

if($_GET["logout"]){
    session_unset();
    session_destroy();
    session_set_cookie_params($config['session_time']); 
    session_name($config['session_name']);
    session_start();
}


?><html lang="en">
  <head>
    <meta name="google-signin-client_id" content="<?php echo $config['google_id'] ?>">
    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="AdminLTE/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="AdminLTE/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="AdminLTE/dist/css/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  </head>
  
  
  <body class="hold-transition login-page">

    <div class="login-box">
      <div class="card">
        <div class="card-body login-card-body">
          <p class="login-box-msg">
            <img src="redondo_login.png">
          </p>

          <div class="social-auth-links text-center mb-3">
            <div id="my-signin2"></div>    
          </div>

        </div>
      </div>
    </div><!-- /.login-box -->



<!-- jQuery -->
<script src="AdminLTE/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="AdminLTE/dist/js/adminlte.min.js"></script>

<script src="https://apis.google.com/js/platform.js?onload=renderButton" async defer></script>

<?php if($_GET["logout"]){ ?>
  <script>
  function renderButton() {
      gapi.signin2.render('my-signin2', {
        'scope': 'profile email',
        'width': 320,
        'height': 50,
        'longtitle': true,
        'theme': 'dark',
        'onsuccess': signOut,
        'onfailure': onFailure
      });
    }
  </script>
<?php }else{ ?>
  <script>
  function renderButton() {
      gapi.signin2.render('my-signin2', {
        'scope': 'profile email',
        'prompt': 'select_account',
        'width': 320,
        'height': 50,
        'longtitle': true,
        'theme': 'dark',
        'onsuccess': onSignIn,
        'onfailure': onFailure
      });
    }
  </script>
<?php }?>

  <script>
    function onSignIn(googleUser) {
        // The ID token you need to pass to your backend:
        var id_token = googleUser.getAuthResponse().id_token;
        window.location.href = "index.php?token=" + id_token;
    }
    function onFailure(error) {
        console.log(error);
    }
    function signOut() {
      var auth2 = gapi.auth2.getAuthInstance();
      auth2.signOut();
      window.location.href = "login.php"; 
    }

  </script>

</body>
</html>



