<div class="container mt-4">
    <div class="row">
        <div class="col-md-9">
            <div class="text-center">
                <h3><?=$auctioneer->name?> - <?=$auctioneer->sub_name?></h3>
                <div class="row justify-content-md-center">
                    <input class="form-control col-md-3" type="date" onchange="update_auction(this)" value="<?=$date?>">
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <?php foreach ($categories as $category) { 
                        if (in_array($category->id, array_column($products, 'product_category_id'))) {;
                    ?>
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 30%"><?=$category->name?></th>
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
                            <?php foreach ($products as $product) { 
                                if ($category->id == $product->product_category_id) {
                            ?>
                            <tr>
                                <th scope="row mr-4"><img src="/assets/img/products/small/<?=$product->product_img?>">
                                    <a href="/producto/<?=strtolower(str_replace(' ', '-', $product->product_name))?>"><?=$product->product_name?></a>
                                </th>
                                <td>
                                  <?php if ($product->prices_avg > $product->last_prices_avg) { ?>
                                    <i class="fas fa-chevron-up text-success"></i>
                                  <?php } elseif ($product->prices_avg < $product->last_prices_avg) { ?>
                                    <i class="fas fa-chevron-down text-danger"></i>
                                  <?php } else { ?>
                                    <i class="fas fa-equals text-info"></i>
                                  <?php } ?>
                                  <?=$product->prices_avg?>
                                </td>
                                <?php foreach ($product->prices as $price) { ?>
                                <td><?=$price?></td>
                                <?php } ?>
                                <?php for ($i=sizeof($product->prices); $i < 10; $i++) { ?>
                                <td></td>
                                <?php } ?>
                            </tr>
                            <?php } } ?>
                        </tbody>
                    </table>
                    <?php } } ?>
                    <?php if (empty($products)) { ?>
                        <p class="text-center">No hay datos para este día.</p>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <h3 class="text-center mb-3">Subastas</h3>
            <ul class="list-group">
                <?php foreach ($auctioneers as $item) { 
                    if ($auctioneer->id == $item->id) { ?>
                <li class="list-group-item active">
                  <img src="/assets/img/auctioneers/small/<?=$item->img?>"><?=$item->name?> - <?=$item->sub_name?>
                    <?php } else {?>
                <li class="list-group-item">
                  <img src="/assets/img/auctioneers/small/<?=$item->img?>"><a href="/subasta/<?=strtolower(str_replace(' ', '-', $item->name).'/'.str_replace(' ', '-', $item->sub_name))?>"><?=$item->name?> - <?=$item->sub_name?></a>
                    <?php } ?>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>


<form id="update_auction" action="" method="post">
  <input type="hidden" name="date" value="">
  <input type="hidden" name="recaptcha" value="">
</form>
