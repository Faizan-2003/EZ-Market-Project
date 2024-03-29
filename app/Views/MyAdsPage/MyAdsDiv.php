<div class="container ml-2" id="myAdsContainer">
    <?php
    if (!empty($loggedUserAds)) {
        foreach ($loggedUserAds as $ad) {
            ?>
            <div class="card mb-3" style="max-width: 900px; position: relative;">
                <div class="row g-0">
                    <div class="col-md-4 col-xl-4">
                        <img src="<?= $ad->getProductImageURI() ?>" class="img-fluid rounded-start">
                    </div>
                    <div class="col-md-8 col-xl-8 d-flex flex-column justify-content-around">
                        <div class="card-body">
                            <h5 class="card-title"><strong><?= $ad->getProductName() ?></strong></h5>
                            <p class="card-text"><?= $ad->getProductDescription() ?></p>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><strong>Price:</strong> €<?= number_format($ad->getProductPrice(), 2, '.') ?></li>
                                <li class="list-group-item"><strong>Status:</strong> <?= $ad->getProductStatus()->value ?></li>
                                <li class="list-group-item"><strong>Posted at:</strong> <?= $ad->getPostedDate() ?></li>
                            </ul>
                        </div>
                        <div class="d-flex justify-content-end mb-2">
                            <?php if ($ad->getProductStatus() === Status::Available) { ?>
                                <button class="btn btn-success mx-2 markAsDone" onclick="btnMarkAsSoldClicked(<?= $ad->getId() ?>)">
                                    <i class="fa-solid fa-check"></i> Mark As Sold
                                </button>
                            <?php } else { ?>
                                <button class="btn btn-success mx-2 markAsDone" disabled>
                                    <i class="fa-solid fa-check"></i> Mark As Sold
                                </button>
                            <?php } ?>
                            <button class="btn btn-warning mx-2" data-bs-toggle="modal" data-bs-target="#editModal" onclick="editAdButtonClicked('<?= $ad->getId() ?>','<?= $ad->getProductImageURI() ?>','<?= $ad->getProductName() ?>','<?= addslashes($ad->getproductDescription()) ?>','<?= $ad->getproductPrice() ?>')">
                                <i class="fa-solid fa-file-pen"></i> Edit
                            </button>
                            <button class="btn btn-danger mx-2" id="btnDeleteAd" name="btnDeleteAd" onclick="btnDeleteAdClicked('<?= $ad->getId() ?>','<?= $ad->getProductImageURI() ?>')">
                                <i class="fa-solid fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
                <?php if ($ad->getproductStatus() !== Status::Available) { ?>
                    <div class="overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center;">
                        <h2 style="color: white;"><?= $ad->getproductStatus()->value ?></h2>
                    </div>
                <?php } ?>
            </div>
            <?php
        }
    } else {
        echo "No ads found for the logged-in user.";
    }
    ?>
</div>
