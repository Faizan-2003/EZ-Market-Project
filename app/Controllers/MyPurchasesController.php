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
    }

    public function displayMyPurchasesPage(): void
    {
        $displayMessage = $this->displayInfo();

        // Check if the logged-in user exists
        if (!is_null($this->loggedUser)) {
            $purchasedAds = $this->adService->getPurchasedAds($this->loggedUser);
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
            $displayMessage = "Please Log in to the system to see your purchased ads.";
        } else {
            $greet = $this->loggedUser ? "Welcome " . $this->loggedUser->getFirstName() : "";
            $displayMessage = $greet . ", " . $this->loggedUser->getFirstName();
        }
        return $displayMessage;
    }

    private function loginAndSignout(): void
    {
        // Add your login and signout logic here
    }
}
