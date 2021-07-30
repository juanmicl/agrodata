<div class="container mt-4 justify-content-md-center">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="card col-md-6">
            <div class="card-body">
            <?php if (!empty($error)) { ?>
                <div class="alert alert-danger" role="alert"><?=$error?></div>
            <?php } elseif (!empty($success)) { ?>
                <div class="alert alert-success" role="alert"><?=$success?></div>
            <?php } ?>
                <h3 class="text-center mb-4"><i class="fas fa-user-cog text-secondary"></i> Ajustes de usuario</h3>
                <form action="?" method="POST">
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Email:</label>
                        <input type="email" class="form-control"
                            value="<?=$user_data->mail?>" name="email" readonly>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Contraseña nueva:</label>
                        <input type="password" class="form-control" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Contraseña antigua:</label>
                        <input type="password" class="form-control" name="old_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">Guardar</button>
                </form>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>
</div>