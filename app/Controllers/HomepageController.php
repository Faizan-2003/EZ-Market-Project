<?php

class HomepageController extends Controller
{
    public function __construct(AdService $adService)
    {
        parent::__construct();
        $this->adService = $adService;
    }

    public function displayHomePage()
    {
        try {
            $ads = $this->adService->getAllAvailableAds(); // only showing available ads
            require __DIR__ . "/../Views/HomePage/Homepage.php";
            $this->showAvailableAds($ads);
            require __DIR__ . '/../Views/Footer.php';
        } catch (Exception $e) {
            // Handle or log the exception
            echo "An error occurred while retrieving ads.";
        }

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
            require __DIR__ . '/../Views/HomePage/NoAdsAvailableToBeSold.html';
        } else {
            require __DIR__ . '/../Views/HomePage/ShowAvailableAds.php';
        }
    }
}
