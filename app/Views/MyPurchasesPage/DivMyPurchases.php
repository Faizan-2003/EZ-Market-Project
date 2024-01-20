<div class="container ml-2 " id="myPurchaseContainer">
<?php
$loggedInUserPurchases = $this->loggedUserPurchases;
if (!empty($loggedInUserPurchases)) {
   // var_dump($loggedInUserPurchases);
    foreach ($loggedInUserPurchases as $purchase) {
        ?>
        <div class="card mb-3" style="max-width: 900px;">
            <div class="row g-0">
                <div class="col-md-4 col-xl-4">
                    <img src="<?= $purchase->getproductImageURI() ?>" class="img-fluid rounded-start" alt="Product Image">
                </div>
                <div class="col-md-8 col-xl-8 d-flex flex-column justify-content-around">
                    <div class="card-body">
                        <h5 class="card-title"><?= $purchase->getProductName() ?></h5>
                        <p class="card-text"><?= $purchase->getproductDescription() ?></p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Price:</strong> €<?= number_format($purchase->getproductPrice(), 2, '.') ?></li>
                            <li class="list-group-item"><strong>Posted at: </strong><?= $purchase->getpostedDate() ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    echo "No purchases found for the logged-in user.";
}
?>
</div>