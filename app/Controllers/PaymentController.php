<?php

Class PaymentController extends Controller{
    public function __construct() {
        parent::__construct();
    }
    public function displayPaymentPage()
    {
        $ads = $this->adService->getAllAvailableAds(); // only showing available ads
        require __DIR__ . "/../Views/Payment/Payment.php";
        $this->showAvailableAds($ads);
        require __DIR__ . '/../Views/Footer.php';
        $this->loginAndSignout();
    }
}