<?php
require_once __DIR__ . '/../Logic/ShoppingCart.php';
require_once __DIR__ . '/../Services/AdService.php';
require_once __DIR__ . '/../Logic/LoggingInAndOut.php';

class PaymentController
{
    private AdService $adService;
    const VAT_RATE = 0.21;

    public function __construct()
    {
        $adRepository = new AdRepository();
        $this->adService = new AdService($adRepository);
    }

    public function displayPaymentPage(): void
    {
        require_once __DIR__ . '/../Views/PaymentPage/PaymentHeader.php';

        // Pass the total to the payment page
        $totalAmount = getTotalAmountOfItemsInShoppingCart();
        $vatAmount = $totalAmount * self::VAT_RATE;
        $total = $totalAmount + $vatAmount;

        $this->checkOutShoppingCart($total);
        require_once __DIR__ . '/../Views/Footer.php';

        $this->loginAndSignout();
    }

    private function checkOutShoppingCart($total): void
    {
        if (isset($_POST["buttonCheckOut"])) {
            if ($this->checkShoppingCartItemsAvailabilityInDb()) {
                $this->updateAdStatusToSold();
                $this->processPurchaseBuyer($total);
                clearShoppingCart();
                require_once __DIR__ . '/../Views/PaymentPage/paymentBody.php';
            } else {
                echo '<script>alert("Some of the products in your shopping cart are not available. Please shop again!")</script>';
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

    private function processPurchaseBuyer($total): void
    {
        $loggedInUser = getLoggedUser();
        if (!is_null($loggedInUser)) {
            $adIDs = $this->adService->processPurchaseBuyer($loggedInUser, $total);
        }
    }
}
