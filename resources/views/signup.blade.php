<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
    <link href="images/favicon.png" rel="icon" />
    <title>Baxe | Inscription</title>
    <meta name="description" content="Baxe website">
    <meta name="author" content="harnishdesign.net">

    <!-- Web Fonts
============================================= -->
    <link rel='stylesheet'
        href='https://fonts.googleapis.com/css?family=Rubik:300,300i,400,400i,500,500i,700,700i,900,900i'
        type='text/css'>

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
        <div class="container-fluid px-0 h-100">
            <div class="row no-gutters h-100">
                <div class="col-md-6">
                    <!-- Get Verified! Text
        ============================================= -->
                    <div class="hero-wrap d-flex align-items-center h-100">
                        <div class="hero-mask opacity-8 bg-primary"></div>
                        <div class="hero-bg hero-bg-scroll" style="background-image:url('./images/bg/image-3.jpg');">
                        </div>
                        <div class="hero-content mx-auto w-100 h-100 d-flex flex-column">
                            <div class="row  no-gutters">
                                <div class="col-10 col-lg-9 mx-auto">
                                    <div class="logo mt-5 mb-5 mb-md-0"> <a class="d-flex" href="index.html"
                                            title="Baxe"><img src="images/logo-light.png"
                                                alt="Baxe"></a> </div>
                                </div>
                            </div>
                            <div class="row my-auto">
                                <div class="col-10 col-lg-9 mx-auto">
                                    <h1 class="text-11 text-white mb-4">S'enrôler !</h1>
                                    <p class="text-4 text-white line-height-4 mb-5">Chaque jour, Baxe rend plus de 1000
                                        utilisateurs heureux</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Get Verified! Text End -->
                </div>
                <div class="col-md-6 d-flex align-items-center">
                    <!-- SignUp Form
        ============================================= -->
                    <div class="container my-4">
                        <div class="row">
                            <div class="col-11 col-lg-9 col-xl-8 mx-auto">
                                <h3 class="font-weight-400 mb-4">S'inscrire</h3>
                                <form id="loginForm" method="post">
                                    <div class="form-group">
                                        <label for="nom">Nom</label>
                                        <input type="text" class="form-control" id="nom" required
                                            placeholder="Entrez votre nom">
                                    </div>
                                    <div class="form-group">
                                        <label for="nom">Prénoms</label>
                                        <input type="text" class="form-control" id="nom" required
                                            placeholder="Entrez votre prénom">
                                    </div>
                                  <div class="row">
                                    <div class="form-group col-6">
                                        <label for="nom">Code postal</label>
                                        <input type="text" class="form-control" id="nom" required
                                            placeholder="Ex : 0000">
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="ville">Ville</label>
                                        <input type="text" class="form-control" id="ville" required
                                            placeholder="Ville de résidence">
                                    </div>
                                  </div>
                                    <div class="form-group">
                                        <label for="emailAddress">Email</label>
                                        <input type="email" class="form-control" id="emailAddress" required
                                            placeholder="Entrez votre adresse email">
                                    </div>
                                    <div class="row">

                                        <div class="form-group col-6">
                                            <label for="nom">Indicatif</label>
                                            <input type="text" class="form-control" id="nom" required
                                                placeholder="Ex : +33">
                                        </div>
                                        <div class="form-group col-6">
                                            <label for="nom">Téléphone</label>
                                            <input type="text" class="form-control" id="nom" required
                                                placeholder="Entrez votre numéro de téléphone (Ex : 00000000)">
                                        </div>
                                    </div>
                                 
                                    <div class="form-group">
                                        <label for="loginPassword">Mot de passe</label>
                                        <input type="password" class="form-control" id="password" required
                                            placeholder="Entrez un mot de passe">
                                    </div>
                                    <div class="form-group">
                                        <label for="loginPassword">Confirmez votre mot de passe</label>
                                        <input type="password" class="form-control" id="password" required
                                            placeholder="Tapez à nouveau le mot de passe">
                                    </div>
                                    <button class="btn btn-primary btn-block my-4" type="submit">S'inscrire</button>
                                </form>
                                <p class="text-3 text-center text-muted">Avez-vous déjà un compte ? <a class="btn-link"
                                        href="{{route('login')}}">Se connecter</a></p>
                            </div>
                        </div>
                    </div>
                    <!-- SignUp Form End -->
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Top
============================================= -->
    <a id="back-to-top" data-toggle="tooltip" title="Back to Top" href="javascript:void(0)"><i
            class="fa fa-chevron-up"></i></a>

    <!-- Script -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/theme.js"></script>
</body>

</html>
