<?php
require __DIR__ . "/../Models/User.php";
require __DIR__ . "/../Models/Ad.php";
require __DIR__ . "/../Services/AdService.php";
require __DIR__ . "/../Logic/LoggingInAndOut.php";
class MyAdsController
{

    private AdService $adService;
    private ?User $loggedUser;
    private ?array $loggedUserAds; // Declare the property explicitly

    public function __construct()
    {
        $adRepository = new AdRepository();
        $this->adService = new AdService($adRepository);

        // Initialize the property before using it
        $this->loggedUserAds = null;

        $this->loggedUser = getLoggedUser();
        if (!is_null($this->loggedUser)) {
            $this->loggedUserAds = $this->adService->getAdsByLoggedUser($this->loggedUser);
        }
    }


    public function displayMyAdsPage(): void
    {
        $displayMessage = $this->displayInfo();

        require __DIR__ . '/../Views/MyAdsPage/MyAds.php';
        $this->showAds();

        require __DIR__ . '/../Views/Footer.php';
        $this->loginAndSignout();
    }

    private function displayInfo(): string
    {
        if (is_null($this->loggedUser)) {
            $displayMessage = "Please Log in to the system to see your ADs or to post new ADs😊.";
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

        if (!is_null($this->loggedUser)) {
            echo '<script>
          //  p.innerHTML = "Log Out";           
            </script>';
        } else {
            echo '<script>
            // Code for not logged-in user (e.g., show login button)
            </script>';
        }

        if (isset($_POST["btnLogin"])) {
            logOutFromApp();
            echo '<script>
          hidePostNewAd();
          loginMessageForSignOut();
          clearScreen();
            </script>';
        }
    }
    private function showAds(): void
    {
        if (!is_null($this->loggedUser)) {
            require __DIR__ . '/../Views/MyAdsPage/EditAd.php';

            $loggedUserAds = $this->adService->getAdsByLoggedUser($this->loggedUser);

            if (!empty($loggedUserAds)) {
                require __DIR__ . '/../Views/MyAdsPage/MyAdsDiv.php';
            } else {
                require __DIR__ . '/../Views/MyAdsPage/NoAdsFound.html';
            }
        }
    }


}
?>
