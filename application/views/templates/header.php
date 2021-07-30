<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AgroData - <?=$seo['title']?></title>
    <meta name="keywords" content="palabras, clave">
    <meta name="description" content="<?=$seo['desc']?>">
    <link rel="shortcut icon" href="/assets/img/favicon.png" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
    <script>
        const global_vars = {
            "recaptcha_v3": "<?=$this->config->item('rcapv3_public')?>",
            "recaptcha_v2": "<?=$this->config->item('rcapv2_public')?>"
        }
    </script>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #dee2e6;">
    <div class="container">
        <a class="navbar-brand mb-0 h1" href="/"><img src="/assets/img/favicon.png" width="30px" style="margin-bottom: 9px; margin-right: 7px;">AGRODATA</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbar">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-chart-line"></i> Precios
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/producto/tomate-pera">Precios por producto</a>
                        <a class="dropdown-item" href="/subasta/la-union/la-redonda">Precios por subasta</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/el-tiempo"><i class="fas fa-cloud-sun"></i> El Tiempo</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/contacto"><i class="far fa-envelope"></i> Contacto</a>
                </li>
            </ul>
            <?php if ($logged_in) { ?>
                <!--<div class="text-success mr-3" title="pro">10</div>-->
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user"></i> <?=$user_data->username?> </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="">
                        <a class="dropdown-item" href="/dashboard/ajustes"><i class="fas fa-cog"></i> Ajustes</a>
                        <a class="dropdown-item" href="/dashboard/api"><i class="fas fa-code"></i> API</a>
                        <a class="dropdown-item" href="/logout"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
                    </div>
                </div>
            <?php } else { ?>
                <button class="btn btn-primary my-2 my-sm-0" href="#" data-toggle="modal" data-target="#login_modal">Iniciar Sesión</button>
            <?php } ?>
        </div>
    </div>
</nav>

<!-- LOGIN MODAL -->
<div class="modal fade" id="login_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Iniciar Sesión</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body ml-4 mr-4">

                <div class="form-group">
                    <input type="text" class="form-control mb-3" name="username" id="login-username"
                        placeholder="Usuario" required>
                    <input type="password" class="form-control" name="password" id="login-password"
                        placeholder="Contraseña" required>
                </div>
                <button type="submit" class="btn btn-primary btn-lg btn-block mt-2"
                    id="login-submit">Login</button>

                <p class="mb-3 mt-3">¿No tienes cuenta aún? <a href="/register" class="text-info">Regístrate
                        ahora</a></p>
            </div>
        </div>
    </div>
</div>

