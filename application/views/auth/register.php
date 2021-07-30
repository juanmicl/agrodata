<?php
    if(!empty($error)){
        echo '
        <script type="text/javascript">
            setTimeout(
            function() {
                swal("", "'.$error.'", "error");
            },
            100
            );
        </script>
        ';
    }
?>

<div class="col-md-4 mx-auto mt-4">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form name="reg" action="?" method="POST">
                <div class="form-group">
                    <label for="exampleInputEmail1">Usuario</label>
                    <input type="text" class="form-control" name="username" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Email</label>
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Contraseña</label>
                    <input type="password" class="form-control" name="pass" placeholder="Contraseña" required>
                    <input type="password" class="form-control" name="rpass" placeholder="Repetir contraseña" required>
                </div>
                <div class="form-group">
                    <div class="g-recaptcha" data-theme="light" data-sitekey="<?=$this->config->item('rcapv2_public')?>"></div>
                </div>
                <button type="submit" class="btn btn-info btn-lg btn-block mt-2"
                    name="register">Registrar</button>
            </form>
        </div>
    </div>
</div>
<script src="https://www.google.com/recaptcha/api.js"></script>