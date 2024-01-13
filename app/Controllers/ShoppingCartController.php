<?php

Class ShoppingCartController extends Controller{
    public function __construct() {
        parent::__construct();
    }
    public function displayShoppingCartPage()
    {
        $ads = $this->adService->getAllAvailableAds(); // only showing available ads
        require __DIR__ . "/../Views/ShoppingCart/ShoppingCart.php";
        $this->showAvailableAds($ads);
        require __DIR__ . '/../Views/Footer.php';
        $this->loginAndSignout();
    }
}