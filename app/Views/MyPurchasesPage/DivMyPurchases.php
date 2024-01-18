<div class="container ml-2" id="myAdsContainer">
    <?php
    $loggedUserAds = getLoggedUserAds(); // Replace with your actual function or data source
    // Check if $loggedUserAds is an array and not empty
    if (is_array($loggedUserAds) && !empty($loggedUserAds)) {
        foreach ($loggedUserAds as $ad) {
            ?>
            <div class="card mb-3" style="max-width: 900px;<?= ($ad->getStatus() !== Status::Available) ? ' position: relative;' : '' ?>">
                <div class="row g-0">
                    <div class="col-md-4 col-xl-4">
                        <img src="<?= $ad->getImageUri() ?>" class="img-fluid rounded-start">
                    </div>
                    <div class="col-md-8 col-xl-8 d-flex flex-column justify-content-around">
                        <div class="card-body">
                            <h5 class="card-title"><?= $ad->getProductName() ?></h5>
                            <p class="card-text"><?= $ad->getDescription() ?></p>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><strong>Price:</strong> €<?= number_format($ad->getPrice(), 2, '.') ?></li>
                               <!-- <li class="list-group-item"><strong>Status:</strong> <?= $ad->getStatus()->value ?></li> -->
                                <li class="list-group-item"><strong>Posted at: </strong><?= $ad->getPostedDate() ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php if ($ad->getStatus() !== Status::Available) { ?>
                    <div class="overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center;">
                        <h2 style="color: white;"><?= $ad->getStatus()->value ?></h2>
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