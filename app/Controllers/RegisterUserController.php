<?php

Class RegisterUserController extends Controller{
    public function __construct() {
        parent::__construct();
    }
    public function displayRegisterUserPage()
    {
        $ads = $this->adService->getAllAvailableAds(); // only showing available ads
        require __DIR__ . "/../Views/RegisterUser/RegisterUser.php";
        $this->showAvailableAds($ads);
        require __DIR__ . '/../Views/Footer.php';
        $this->loginAndSignout();
    }
}