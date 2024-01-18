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
        //session_start();

        $this->addItemToCart();
        $this->removeItemFromCart();
        require __DIR__ . '/../Views/ShoppingCart/shoppingCartHeader.php';
        $this->checkCartItemsAndDisplayAccordingly();
        require __DIR__ . '/../Views/Footer.php';
        $this->loginAndSignout();
    }
    private function checkCartItemsAndDisplayAccordingly(): void
    {
        if (!isset($_SESSION['cartItems']) || !is_array($_SESSION['cartItems']) || empty($_SESSION['cartItems'])) {
            require __DIR__ . '/../Views/ShoppingCart/ShoppingCartEmpty.html';
        } else {
            $this->total = getTotalAmountOfItemsInShoppingCart();
            $this->vatAmount = $this->total * self::Vat_Rate;
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
        if (isset($_POST["removeCartItem"])) {
            $ad = $this->adService->getAdByID(htmlspecialchars($_POST['hiddenSHoppingCartItemID']));
            if (isset($_SESSION['cartItems']) && is_array($_SESSION['cartItems']) && checkTheExistenceOfItemInCart($ad)) {
                removeItemFromCart($ad);
            }
        }
        updateItemCountInSession();
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
