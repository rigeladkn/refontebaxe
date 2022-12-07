<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
    <link href="{{ asset('images/favicon.png') }}" rel="icon" />
    <title>Baxe | Login</title>
    <meta name="description" content="Baxe website">
    <meta name="author" content="OFIELD GROUP">

    <!-- Web Fonts
============================================= -->
    <link rel='stylesheet'
        href='https://fonts.googleapis.com/css?family=Rubik:300,300i,400,400i,500,500i,700,700i,900,900i'
        type='text/css'>

    <!-- Stylesheet
============================================= -->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/font-awesome/css/all.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/stylesheet.css') }}" />
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
                <!-- Welcome Text
      ============================================= -->
                <div class="col-md-6">
                    <div class="hero-wrap d-flex align-items-center h-100">
                        <div class="hero-mask opacity-8 bg-primary"></div>
                        <div class="hero-bg hero-bg-scroll"
                            style="background-image:url('{{ asset('images/bg/image-3.jpg') }}');"></div>
                        <div class="hero-content mx-auto w-100 h-100 d-flex flex-column">
                            <div class="row no-gutters">
                                <div class="col-10 col-lg-9 mx-auto">
                                    <div class="logo mt-1 mb-5 mb-md-0"> <a class="d-flex" href="index.html"
                                            title="Lisocache"><img src="{{ asset('images/logo.png') }}" style="height: 10%; width : 100%"
                                                alt="Lisocache"></a> </div>
                                </div>
                            </div>
                            <div class="row no-gutters my-auto">
                                <div class="col-10 col-lg-9 mx-auto">
                                    <h1 class="text-11 text-white mb-4">Bienvenue !</h1>
                                    <p class="text-4 text-white line-height-4 mb-5">Nous sommes ravis de vous revoir.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Welcome Text End -->

                <!-- Login Form
      ============================================= -->
                <div class="col-md-6 d-flex align-items-center">
                    <div class="container my-4">
                        <div class="row">
                            <div class="col-11 col-lg-9 col-xl-8 mx-auto">
                                <h3 class="font-weight-400 mb-4">Se connecter</h3>
                                <form id="" method="post" action="/login">
                                    @csrf
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" name="email" required
                                            placeholder="Entrez votre adresse email">
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Mot de passe</label>
                                        <input type="password" class="form-control" name="password" required
                                            placeholder="Entrez votre mot de passe">
                                    </div>
                                    <div class="row">
                                        <div class="col-sm">
                                            <div class="form-check custom-control custom-checkbox">
                                                <input id="remember-me" name="remember" class="custom-control-input"
                                                    type="checkbox">
                                                <label class="custom-control-label" for="remember-me">Se rappeler de
                                                    moi</label>
                                            </div>
                                        </div>
                                        <div class="col-sm text-right"><a class="btn-link" href="#">Mot de passe
                                                oublié ?</a></div>
                                    </div>
                                    <button class="btn btn-primary btn-block my-4" type="submit">Se connecter</button>
                                </form>
                                <p class="text-3 text-center text-muted">Vous n'avez pas de compte ? <a class="btn-link"
                                        href="{{ route('signup') }}">S'inscrire</a></p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Login Form End -->
            </div>
        </div>
    </div>

    <!-- Back to Top
============================================= -->
    <a id="back-to-top" data-toggle="tooltip" title="Back to Top" href="javascript:void(0)"><i
            class="fa fa-chevron-up"></i></a>

    <!-- Script -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/theme.js') }}"></script>
</body>

</html>
