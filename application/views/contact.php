<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body text-center">
                    <h3><i class="far fa-envelope"></i> Contacto</h3>
                    <?php if (!empty($error)) { ?>
                        <div class="alert alert-danger">
                            Error: <?=$error?>
                        </div>
                    <?php } elseif (!empty($success)) { ?>
                        <div class="alert alert-success">
                            <?=$success?>
                        </div>
                    <?php } ?>
                    <form action="?" method="POST">
                        <div class="form-row mt-3">
                            <div class="col-md">
                                <input name="name" type="text" class="form-control" placeholder="Nombre" required>
                            </div>
                            <div class="col-md">
                                <input name="email" type="text" class="form-control" placeholder="tucorreo@email.com" required>
                            </div>
                        </div>
                        <div class="form-row mt-3">
                            <div class="col-md">
                                <textarea name="message" class="form-control" rows="5" placeholder="Mensaje..." required></textarea>
                            </div>
                        </div>
                        <div class="form-group d-flex justify-content-center mt-3">
                            <div class="g-recaptcha" data-theme="light" data-sitekey="<?=$this->config->item('rcapv2_public')?>"></div>
                        </div>
                        <button type="submit" class="btn btn-primary mb-2">Enviar mensaje</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://www.google.com/recaptcha/api.js"></script>
<script src="/assets/js/app.js"></script>