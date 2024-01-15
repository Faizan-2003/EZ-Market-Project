<?php

Class  MyPurchasesController extends Controller{
    public function __construct() {
        parent::__construct();
    }
    public function displayMyPurchasesPage()
    {
        $ads = $this->adService->getAllAvailableAds(); // only showing available ads
        require __DIR__ . "/../Views/MyPurchasespage/MyPurchases.php";
        $this->showAvailableAds($ads);
        require __DIR__ . '/../Views/Footer.php';
        $this->loginAndSignout();
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