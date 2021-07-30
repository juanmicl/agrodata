<div class="container mt-4">
    <div class="row">
        <div class="col-md-9">
            <div class="text-center">
                <h3><?=$product->name?></h3>
                <div class="row justify-content-md-center">
                    <input onchange="update_product(this)" class="form-control col-md-3" type="date"
                        value="<?=$date?>">
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <?php if (!empty($auctions)) { ?>
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th scope="col">Subastas</th>
                                <!--<?php for ($i=1; $i <= $max_lenght; $i++) { ?> 
                                        <th scope="col"><?=$i?></th>
                                    <?php } ?>-->
                                <th scope="col">Media</th>
                                <th scope="col">1</th>
                                <th scope="col">2</th>
                                <th scope="col">3</th>
                                <th scope="col">4</th>
                                <th scope="col">5</th>
                                <th scope="col">6</th>
                                <th scope="col">7</th>
                                <th scope="col">8</th>
                                <th scope="col">9</th>
                                <th scope="col">10</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($auctions as $auction) { ?>
                            <tr>
                                <th scope="row"><img src="/assets/img/auctioneers/small/<?=$auction->auctioneer_img?>">
                                    <a
                                        href="/subasta/<?=strtolower(str_replace(' ', '-', $auction->auctioneer_name).'/'.str_replace(' ', '-', $auction->auctioneer_sub_name))?>"><?=$auction->auctioneer_name?> - <?=$auction->auctioneer_sub_name?></a>
                                </th>
                                <td>
                                    <?php if ($auction->prices_avg > $auction->last_prices_avg) { ?>
                                        <i class="fas fa-chevron-up text-success"></i>
                                    <?php } elseif ($auction->prices_avg < $auction->last_prices_avg) { ?>
                                        <i class="fas fa-chevron-down text-danger"></i>
                                    <?php } else { ?>
                                        <i class="fas fa-equals text-info"></i>
                                    <?php } ?>
                                    <?=$auction->prices_avg?>
                                </td>
                                <?php foreach ($auction->prices as $price) { ?>
                                <td><?=$price?></td>
                                <?php } ?>
                                <?php for ($i=sizeof($auction->prices); $i < 10; $i++) { ?>
                                <td></td>
                                <?php } ?>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php } else { ?>
                        <p class="text-center">No hay datos para este día.</p>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <h3 class="text-center mb-3">Productos</h3>
            <div class="accordion" id="accordion">
                <?php foreach ($categories as $key => $category) { ?>
                <div class="card">
                    <div class="card-header" id="heading<?=$key?>">
                        <h5 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse"
                                data-target="#collapse<?=$key?>">
                                <img src="/assets/img/products/small/<?=$category->img?>"> <?=$category->name?>
                            </button>
                        </h5>
                    </div>

                    <div id="collapse<?=$key?>"
                        class="collapse <?php if ($category->id == $product->category_id) { echo 'show'; }?>"
                        aria-labelledby="heading<?=$key?>" data-parent="#accordion">
                        <ul class="list-group">
                            <?php foreach ($products as $item) { 
                                if ($item->category_id == $category->id){
                            ?>
                            <li class="list-group-item">
                                <a
                                    href="/producto/<?=strtolower(str_replace(' ', '-', $item->name))?>"><?=$item->name?></a>
                            </li>
                            <?php } } ?>
                        </ul>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<form id="update_product" action="" method="post">
    <input type="hidden" name="date" value="">
    <input type="hidden" name="recaptcha" value="">
</form>