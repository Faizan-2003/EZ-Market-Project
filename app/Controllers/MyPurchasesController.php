<?php

require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/Ad.php';
require_once __DIR__ . '/../Services/AdService.php';
require_once __DIR__ . '/../Logic/LoggingInAndOut.php';

class MyPurchasesController
{
    private AdService $adService;
    private ?User $loggedUser;

    public function __construct()
    {
        $adRepository = new AdRepository();
        $this->adService = new AdService($adRepository);
        $this->loggedUser = getLoggedUser();  // Use the function directly

        var_dump($this->loggedUser);  // Check if it's retrieving the user
    }

    public function displayMyPurchasesPage(): void
    {
        session_start();

        $displayMessage = $this->displayInfo();

        // Check if the logged-in user exists
        if (!is_null($this->loggedUser)) {
           // $purchasedAds = $this->adService->getPurchasedAds($this->loggedUser);
        } else {
            $purchasedAds = null;
        }

        require __DIR__ . '/../Views/MyPurchasesPage/MyPurchases.php';
        require __DIR__ . '/../Views/Footer.php';
        $this->loginAndSignout();
    }

    private function displayInfo(): string
    {
        if (is_null($this->loggedUser)) {
            $displayMessage = "Please Log in to the system to see your purchased products😊.";
        } else {
            $displayMessage = $this->loggedUser ? "Welcome, " . $this->loggedUser->getFirstName() : "";
        }
        return $displayMessage;
    }

    private function loginAndSignout(): void
    {
        if (!is_null($this->loggedUser)) {
            echo '<script>            
            showPostNewAd();
            </script>';
        } else {
            echo '<script>
          hidePostNewAd();
            </script>';
        }

        // Check if the user is logged in and update the button accordingly
        if (!is_null($this->loggedUser)) {
            echo '<script>
            p.innerHTML = "Log Out";           
            </script>';
        } else {
            echo '<script>
            // Code for not logged-in user (e.g., show login button)
            </script>';
        }

        if (isset($_POST["btnlogin"])) {
            logOutFromApp();
            echo '<script>
          hidePostNewAd();
          loginMessageForSignOut();
          clearScreen();
            </script>';
        }
    }
}
