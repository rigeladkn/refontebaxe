<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
<link href="images/favicon.png" rel="icon" />
<title>Vérification de code SMS</title>
<meta name="description" content="">
<meta name="author" content="OFIELD GROUP">

<!-- Web Fonts
============================================= -->
<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Rubik:300,300i,400,400i,500,500i,700,700i,900,900i' type='text/css'>

<!-- Stylesheet
============================================= -->
<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="vendor/font-awesome/css/all.min.css" />
<link rel="stylesheet" type="text/css" href="css/stylesheet.css" />
</head>
<body>

<!-- Preloader -->
<div id="preloader">
  <div data-loader="dual-ring"></div>
</div>
<!-- Preloader End -->

<div id="main-wrapper" class="h-100">
  <div class="container h-100">
    <!-- Login Form
    ============================================= -->
    <div class="row no-gutters h-100">
      <div class="col-11 col-sm-9 col-md-7 col-lg-5 col-xl-4 m-auto">
        <div class="logo mb-4 text-center"> <a href="/" title="Baxe"><img src="images/logo.png" alt="Baxe"></a> </div>
        <form id="smsVerificationForm" method="post">
          <div class="vertical-input-group">
            <div class="input-group">
              <input type="text" class="form-control" name="smsCode" required placeholder="Entrez le code reçu sur votre numéro">
            </div>
          </div>
          <button class="btn btn-primary btn-block shadow-none my-4" type="submit">Vérifier</button>
        </form>
        <form action="" method="POST">
            <div class="">
                <p class="text-center ml-auto">
                    <a class="btn-link" href="#">Renvoyer le code</a>
                </p>
            </div>
        </form>
      </div>
      {{-- <div class="col-12 fixed-bottom">
        <p class="text-center text-1 text-muted mb-1">Copyright © 2019 <a href="#">Payyed</a>. All Rights Reserved.</p>
      </div> --}}
    </div>
    <!-- Login Form End -->
  </div>
</div>

<!-- Back to Top
============================================= --> 
<a id="back-to-top" data-toggle="tooltip" title="Back to Top" href="javascript:void(0)"><i class="fa fa-chevron-up"></i></a> 

<!-- Script --> 
<script src="vendor/jquery/jquery.min.js"></script> 
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script> 
<script src="js/theme.js"></script>
</body>
</html>