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
    private ?array $loggedUserPurchases; // Declare the property explicitly

    public function __construct()
    {
        $adRepository = new AdRepository();
        $this->adService = new AdService($adRepository);

        // Initialize the property before using it
        $this->loggedUserPurchases = null;

        $this->loggedUser = getLoggedUser();
        if (!is_null($this->loggedUser)) {
            $this->loggedUserPurchases = $this->adService->getPurchasesByLoggedInUser($this->loggedUser);
        }
        // var_dump($this->loggedUser);  // Check if it's retrieving the user
    }

    public function displayMyPurchasesPage(): void
    {
        $displayMessage = $this->displayInfo();
        $purchasedAds = [];

        require __DIR__ . '/../Views/MyPurchasesPage/MyPurchases.php';
        // Call the showPurchases method to display purchased ads
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
    public function setLoggedUser(?User $loggedUser): void
    {
        $this->loggedUser = $loggedUser;
        if (!is_null($this->loggedUser)) {
            $this->loggedUserPurchases = $this->adService->getPurchasesByLoggedInUser($this->loggedUser);
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
