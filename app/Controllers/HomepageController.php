<?php
require __DIR__ . "/../Models/User.php";
require __DIR__ . "/../Models/Ad.php";
require __DIR__ . "/../Services/AdService.php";
require  __DIR__ . "/../Logic/LoggingInAndOut.php";

class HomepageController
{
    private AdService $adService;

    public function __construct(AdService $adService)
    {
        $this->adService = $adService;
    }

    public function displayHomePage()
    {
        $ads = $this->adService->getAllAvailableAds(); // only showing available ads
        require __DIR__ . "/../Views/Homepage/Homepage.php";
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

    private function showAvailableAds($ads): void
    {
        if (is_null($ads)) {
            require __DIR__ . '/../Views/HomePage/NoAvailableAds.html';
        } else {
            require __DIR__ . '/../Views/HomePage/DisplayAvailableAds.php';
        }
    }
}
