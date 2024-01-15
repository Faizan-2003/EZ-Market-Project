<?php
require_once __DIR__ . "/../Models/User.php";
require_once __DIR__ . "/../Models/Ad.php";
require_once __DIR__ . "/../Services/AdService.php";
require_once __DIR__ . "/../Logic/LoggingInAndOut.php";

class HomepageController
{
    private AdService $adService;

    public function __construct(AdService $adService)
    {
        $this->adService = $adService;
    }

    public function displayHomePage()
    {
        $ads = $this->adService->getAllAvailableAds();

        $this->loginAndSignout();
        $this->renderHomepageView($ads);
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

    private function renderHomepageView($ads): void
    {
        // You can now include the view files after processing the data
        require __DIR__ . "/../Views/Homepage/Homepage.php";
        $this->showAvailableAds($ads);
        require __DIR__ . '/../Views/Footer.php';
    }

    private function showAvailableAds($ads): void
    {
        if (is_null($ads)) {
            require __DIR__ . '/../Views/HomePage/NoAvailableAds.html';
        } else {
            try {
                require __DIR__ . '/../Views/HomePage/DisplayAvailableAds.php';
            } catch (Exception $e) {
                error_log('Error in DisplayAvailableAds.php: ' . $e->getMessage());
            }
        }
    }
}