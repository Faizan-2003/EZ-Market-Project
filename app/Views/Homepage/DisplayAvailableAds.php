<div class="container">
    <div class="row py-3 text-center" id="containerRowContainerAvailableAds">
        <?php
        if (!empty($ads) && is_array($ads)) {
            foreach ($ads as $ad) {
                ?>
                <div class="col-md-4 col-sm-12 col-xl-3 my-3">
                    <div class="card h-100 shadow">
                        <div class="img-container with-border">
                            <img src="<?= $ad->getProductImageURI() ?>" class="img-fluid card-img-top" alt="<?= $ad->getProductName() ?>">
                        </div>
                        <div class="card-body">
                            <h3 class="card-title"><?= $ad->getProductName() ?></h3>
                            <p class="card-text"><?= $ad->getProductDescription() ?></p>
                        </div>
                        <button id="AddToCart" class="btn btn-primary" type="submit" onclick="addToCartClicked(<?= $ad->getId() ?>)">
                            <i class="fa-solid fa-cart-plus"></i>
                            €<?= number_format($ad->getProductPrice(), 2, '.') ?>
                        </button>
                        <div class="card-footer">
                            <p class="card-text">
                                <small class="text-black"><?= $ad->getPostedDate() ?> posted by</small>
                                <strong><?= $ad->getUserID()->getFirstName() ?></strong>
                            </p>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo '<p>No ads available</p>';
        }
        ?>
    </div>
</div>
