<div class="container">
    <div class="row">
        <div class="col-12 pt-5">
            <h1 class="text-center paymentText">Payment Successful😍!</h1>
            <p class="text-center thankyouText">Thank you for your purchase! You have paid <strong>€ <?= htmlspecialchars_decode(number_format($total, 2, '.')) ?></strong>
                , Your payment has been successfully processed, and your product will be shipped soon🚚...</p>
            <div class="text-center">
                <button class="btn btn-primary btn-Shop" onClick="window.location.href='/homepage'">Shop Again</button>
            </div>
        </div>
    </div>
</div>