<?php
class Router
{
    public function route($uri)
    {
        $uri = $this->stripParameters($uri);

        switch ($uri) {
            case '':
            case 'homepage':
                require __DIR__ . "/../Controllers/HomepageController.php";
                $controller = new HomepageController();
                $controller->displayHomePage();
                break;
            case 'homepage/login':
                require __DIR__ . '/../Controllers/LoginController.php';
                $controller = new LoginController();
                $controller->displayLoginPage();
                break;
            case 'homepage/login/signup':
                require __DIR__ . '/../Controllers/RegisterUserController.php';
                $controller=new RegisterUserController();
                $controller->displayRegisterUserPage();
                break;
            case 'homepage/myAds':
                require __DIR__ . '/../Controllers/MyAdsController.php';
                $controller = new MyAdsController();
                $controller->displayMyAdsPage();
                break;
            case 'homepage/mypurchases':
                require __DIR__ . '/../Controllers/MyPurchasesController.php';
                $controller = new MyPurchasesController();
                $controller->displayMyPurchasesPage();
                break;
            case 'homepage/shoppingCart':
                require __DIR__ . '/../Controllers/ShoppingCartController.php';
                $controller = new ShoppingCartController();
                $controller->displayShoppingCartPage();
                break;
            case 'homepage/shoppingCart/payment':
                require __DIR__ . '/../Controllers/PaymentController.php';
                $controller = new Pay();
                $controller->displayPaymentPage();
                break;
            case 'api/adsapi':
                require __DIR__ . '/../API/AdsController.php';
                $controller = new AdsController();
                $controller->postNewAdRequest();
                break;
            case 'api/adsbyloggeduser':
                require __DIR__ . '/../API/AdsController.php';
                $controller = new AdsController();
                $controller->sendAdsByLoggedUser();
                break;
            case 'api/updateAd':
                require __DIR__ . '/../API/AdsController.php';
                $controller = new AdsController();
                $controller->operateAdRequest();
                break;
            case 'api/editAd':
                require __DIR__ . '/../API/AdsController.php';
                $controller = new AdsController();
                $controller->handleAdEditRequest();
                break;
            case 'api/searchproducts':
                require __DIR__ . '/../API/AdsController.php';
                $controller = new AdsController();
                $controller->handleSearchRequest();
                break;
            default:
                http_response_code(404);
                break;
        }
    }

    private function stripParameters($uri)
    {
        if (str_contains($uri, '?')) {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }
        return $uri;
    }
}
