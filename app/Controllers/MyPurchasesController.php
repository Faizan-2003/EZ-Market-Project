<?php

require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/Ad.php';
require_once __DIR__ . '/../Services/AdService.php';
require_once __DIR__ . '/../Logic/LoggingInAndOut.php';
require_once __DIR__ . '/../Controllers/PaymentController.php';

class MyPurchasesController
{
    private AdService $adService;
    private ?User $loggedUser;
    private ?array $loggedUserPurchases;

    public function __construct()
    {
        $adRepository = new AdRepository();
        $this->adService = new AdService($adRepository);

        $this->loggedUserPurchases = null;

        $this->loggedUser = getLoggedUser();
        if (!is_null($this->loggedUser)) {
            $this->loggedUserPurchases = $this->adService->getPurchasesByLoggedInUser($this->loggedUser);
        }
    }

    public function displayMyPurchasesPage(): void
    {
        $displayMessage = $this->displayInfo();
        $purchasedAds = [];

        require __DIR__ . '/../Views/MyPurchasesPage/MyPurchases.php';
        $this->showPurchases();
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
    }
    private function showPurchases(): void
    {
        if (!is_null($this->loggedUser)) {
            $loggedUserPurchases = $this->adService->getPurchasesByLoggedInUser($this->loggedUser);
            if (!empty($loggedUserPurchases)) {
                require __DIR__ . '/../Views/MyPurchasesPage/DivMyPurchases.php';
                echo '<script>displayPurchasedAd(ad);</script>';
            } else {
                require __DIR__ . '/../Views/MyPurchasesPage/NoPurchasesMade.html';
            }
        }
    }
}
