<?php
require __DIR__ . '/../Services/AdService.php';
require __DIR__ . '/../Logic/ShoppingCart.php';
require __DIR__ . '/../Logic/LoggingInAndOut.php';

class ShoppingCartController
{
    private $adService;
    private $total;
    private $vatAmount;
    const Vat_Rate = 0.21;

    public function __construct()
    {
        $adRepository = new AdRepository();
        $this->adService = new AdService($adRepository);
    }
    public function displayShoppingCartPage(): void
    {
        $totalAmount = getTotalAmountOfItemsInShoppingCart();
        $vatAmount = $totalAmount * self::Vat_Rate;
        $total = $totalAmount + $vatAmount;

        $this->addItemToCart();
        $this->removeItemFromCart();

        require __DIR__ . '/../Views/ShoppingCart/shoppingCartHeader.php';
        $this->checkCartItemsAndDisplayAccordingly($vatAmount, $total, $totalAmount);
        require __DIR__ . '/../Views/Footer.php';
        $this->loginAndSignout();
    }
    public function checkCartItemsAndDisplayAccordingly($vatAmount, $total, $totalAmount): void
    {
        if (!isset($_SESSION['cartItems']) || !is_array($_SESSION['cartItems']) || empty($_SESSION['cartItems'])) {
            require __DIR__ . '/../Views/ShoppingCart/ShoppingCartEmpty.html';
        } else {
            $this->total = $total; // Set the total property for later use
            $this->vatAmount = $vatAmount; // Set the vatAmount property for later use
            require __DIR__ . '/../Views/ShoppingCart/ShoppingCartDisplayProduct.php';
        }
    }
    private function addItemToCart(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            if (isset($_POST["AdID"])) {
                try {
                    $dbAd = $this->adService->getAdByID(htmlspecialchars($_POST["AdID"]));
                    if ($dbAd->getProductStatus() !== Status::Available) {
                        echo "<script>alert('This product is already sold')</script>";
                        echo "<script>location.href = '/homepage'</script>";
                        exit();
                    } else {
                        if (!isset($_SESSION['cartItems']) || !is_array($_SESSION['cartItems'])) {
                            $_SESSION['cartItems'] = [];
                        }

                        if (!checkTheExistenceOfItemInCart($dbAd)) {
                            addItemToShoppingCart($dbAd);
                        }
                    }
                } catch (Exception $e) {
                    echo "Error: " . $e->getMessage();
                }
            }
        }
        updateItemCountInSession();
    }
    private function removeItemFromCart(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (isset($_POST["removeCartItem"]) && isset($_POST['hiddenShoppingCartItemID'])) {
                $ad = $this->adService->getAdByID(htmlspecialchars($_POST['hiddenShoppingCartItemID']));

                if (isset($_SESSION['cartItems']) && is_array($_SESSION['cartItems']) && checkTheExistenceOfItemInCart($ad)) {
                    removeItemFromCart($ad);
                }
            }
            updateItemCountInSession();
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
}