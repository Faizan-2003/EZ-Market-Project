<section class="h-custom" style="background-color: beige;">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h3 class="mb-3">Shopping Cart</h3>
                                    <a href="/homepage" class="btn btn-secondary btn-lg" style="margin-right: 10px;">Continue Shopping</a>
                                </div>
                                <p>You have <?= count($_SESSION['cartItems']) ?> items in your cart</p>
                                <?php foreach ($_SESSION['cartItems'] as $ad): ?>
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5><?= htmlspecialchars($ad->getProductName()) ?></h5>
                                            <p><?= htmlspecialchars($ad->getProductDescription()) ?></p>
                                            <p>Price: €<?= number_format($ad->getProductPrice(), 2, '.') ?></p>

                                            <form method="POST">
                                                <input type="hidden" name="hiddenShoppingCartItemID" value="<?= $ad->getId() ?>">
                                                <!-- Larger red bin -->
                                                <button name="removeCartItem" type="submit" class="btn btn-danger btn-lg" style="position: absolute; bottom: 5px; right: 5px;">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                                <hr class="my-4">

                                <div class="card-footer">
                                    <div class="d-flex d-md-flex justify-content-between">
                                        <p class="mb-2"><strong>Total (Excl. taxes)</strong></p>
                                        <p class="mb-2">€<?= htmlspecialchars_decode(number_format($totalAmount ?? 0, 2, '.')) ?></p>
                                    </div>
                                    <div class="d-flex d-md-flex justify-content-between">
                                        <p class="mb-2"><strong>VAT Amount (21%)</strong></p>
                                        <p class="mb-2">€<?= htmlspecialchars_decode(number_format($vatAmount ?? 0, 2, '.')) ?></p>
                                    </div>
                                    <div class="d-flex d-md-flex justify-content-between mb-4">
                                        <p class="mb-2"><strong>Total (Incl. taxes)</strong></p>
                                        <p class="mb-2">€<?= htmlspecialchars_decode(number_format($total ?? 0, 2, '.')) ?></p>
                                    </div>
                                    <form method="POST" action="/homepage/shoppingCart/payment">
                                        <button name="buttonCheckOut" type="submit" class="btn btn-block btn-lg d-sm-block float-right" style="float: right !important; background-color:#ffb703;">
                                            <div class="d-flex">
                                                <span>Checkout € <strong><?= htmlspecialchars_decode(number_format($total, 2, '.')) ?></strong><i class="fas fa-long-arrow-alt-right ms-2"></i></span>
                                            </div>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>