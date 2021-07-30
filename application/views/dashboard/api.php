<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.7.2/highlight.min.js"
    integrity="sha512-s+tOYYcC3Jybgr9mVsdAxsRYlGNq4mlAurOrfNuGMQ/SCofNPu92tjE7YRZCsdEtWL1yGkqk15fU/ark206YTg=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
hljs.highlightAll();
</script>

<div class="container mt-4 justify-content-md-center">
    <div class="row">
        <div class="card col-md-7">
            <div class="card-body text-center">
                <h3><i class="fas fa-key text-warning"></i> API Key</h3>
                <div class="input-group mt-3">
                    <input class="form-control text-center" value="<?=$user_data->api_key?>" id="api_key"
                        onclick="copy_text(this)" data-toggle="copy_text" data-placement="bottom"
                        title="Click para copiar" readonly>
                    <div class="input-group-append">
                        <button class="btn btn-success" type="button" onclick="renew_api_key(this)"
                            data-toggle="renew_token" data-placement="bottom" title="Renovar API key">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="mb-3"><i class="far fa-chart-bar text-primary"></i> Límites y estadísticas</h3>
                    <p>Límite de requests diario:</p>
                    <?php if ($api['n_requests'] > 0) { ?>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: <?=$api['n_requests']?>%;"
                            aria-valuenow="<?=$api['n_requests']?>" aria-valuemin="0" aria-valuemax="100">
                            <?=$api['n_requests']?>%</div>
                    </div>
                    <?php } else { ?>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 100%;" aria-valuenow="0"
                            aria-valuemin="0" aria-valuemax="100">0/100</div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="card col-md-12 mt-4">
            <div class="card-body text-center">
                <h3><i class="fas fa-book text-info"></i> Documentación</h3>
                <p>Esta es la documentación necesaria para el uso de nuestra API.</p>
                <h4><a href="#autenticacion">1</a> Autenticación</h4>
                <p>Para poder hacer uso de la API es necesario autenticarse en cada request a través de la API Key
                    proporcionada anteriormente.</p>
                <h4><a href="#api-key">1.1</a> API Key</h4>
                <p>Se proporcionará a través del parámetro <code class="code">X-API-KEY</code> en el header de la
                    request.</p>
                <p><code class="plaintext">X-API-KEY: 946e7b-b49476-af5eb6-26ec91-df0dd0</code></p>
                <h4><a href="#headers">1.2</a> Headers</h4>
                <p>El servidor responderá en cada request con los siguientes headers.</p>
                <div class="row justify-content-md-center">
                    <table class="table table-striped col-8">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Parámetro</th>
                                <th scope="col">Descripción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td><code class="code">X-Rate-Limit-Requests-Quota</code></td>
                                <td>Límite de requests.</td>
                            </tr>
                            <tr>
                                <th scope="row">2</th>
                                <td><code class="code">X-Rate-Limit-Requests-Left</code></td>
                                <td>Número de requests restantes para llegar al límite.</td>
                            </tr>
                            <tr>
                                <th scope="row">3</th>
                                <td><code class="code">X-Rate-Limit-Time-Reset</code></td>
                                <td>Fecha en la que se reestablecen los límites de nuevo.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <h4><a href="#endpoints">2</a> Endpoints</h4>
                <p>La API consta de 4 endpoints:</p>
                <div class="row justify-content-md-center">
                    <table class="table table-striped col-12">
                        <thead>
                            <tr>
                                <th scope="col">Método</th>
                                <th scope="col">Endpoint</th>
                                <th scope="col">Descripción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">GET</th>
                                <td><code class="code">/api/auctioneers</code></td>
                                <td>Obtener listado de todos los subastadores.</td>
                            </tr>
                            <tr>
                                <th scope="row">GET</th>
                                <td><code class="code">/api/auction/auctioneer_id/YYYY-mm-dd</code></td>
                                <td>Obtener datos de la subasta por fecha e id de subastador.</td>
                            </tr>
                            <tr>
                                <th scope="row">GET</th>
                                <td><code class="code">/api/products</code></td>
                                <td>Obtener listado de todos los productos.</td>
                            </tr>
                            <tr>
                                <th scope="row">GET</th>
                                <td><code class="code">/api/product/product_id/YYYY-mm-dd</code></td>
                                <td>Obtener datos del producto por fecha e id de producto.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$('[data-toggle="copy_text"]').tooltip();
$('[data-toggle="renew_token"]').tooltip();
</script>