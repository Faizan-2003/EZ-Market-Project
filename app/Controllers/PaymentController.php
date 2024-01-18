<?php
require __DIR__ . '/../Logic/ShoppingCart.php';
require __DIR__ . '/../Services/AdService.php';
require  __DIR__ . '/../Logic/LoggingInAndOut.php';
class PaymentController
{
    private $adService;
    const Vat_Rate = 0.21;

    public function __construct()
    {
        $adRepository = new AdRepository();
        $this->adService = new AdService($adRepository);    }

    public function displayPaymentPage(): void
    {
        require_once __DIR__ . '/../Views/PaymentPage/PaymentHeader.php';

        // Pass the total to the payment page
        $totalAmount = getTotalAmountOfItemsInShoppingCart();
        $vatAmount = $totalAmount * self::Vat_Rate;
        $total = $totalAmount + $vatAmount;

        $this->checkOutShoppingCart($total);
        require_once __DIR__ . '/../Views/Footer.php';

        $this->loginAndSignout();
    }




    private function checkOutShoppingCart($total): void
    {
        if (isset($_POST["buttonCheckOut"])) {
            if ($this->checkShoppingCartItemsAvailabilityInDb()) {
                $total;
                $this->updateAdStatusToSold();
                clearShoppingCart();
                require __DIR__ . '/../Views/PaymentPage/paymentBody.php';
            } else {
                echo '<script>alert("Some the products in your shopping are not available, please shop again!")</script>';
                clearShoppingCart();
                echo '<script>location.href = "/homepage/myAds"</script>';
            }
        }
    }

    private function updateAdStatusToSold(): void
    {
        $items = getItemsInShoppingCart();
        foreach ($items as $item) {
            $this->adService->markAdAsSold($item->getId());
        }
    }
    private function loginAndSignout(): void
    {
        if (!is_null(getLoggedUser())) {
            echo '<script>disableLoginButton();</script>';
        }
        if (isset($_POST["btnSignOut"])) {
            logOutFromApp();
            echo '<script>enableLogin()</script>';
        }
    }

    private function checkShoppingCartItemsAvailabilityInDb(): bool
    {
        $items = getItemsInShoppingCart();
        foreach ($items as $item) {
            $dbAd = $this->adService->getAdByID($item->getId());
            if ($dbAd->getProductStatus() !== Status::Available) {
                return false;
            }
        }
        return true;
    }
}
