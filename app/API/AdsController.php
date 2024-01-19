<?php
require __DIR__ . '/../Services/AdService.php';
require_once __DIR__ . '/../Models/Ad.php';
require_once __DIR__ . '/../Models/User.php';

class AdsController
{
    private $adService;

    public function __construct()
    {
        $adRepository = new AdRepository();
        $this->adService = new AdService($adRepository);
    }
    public function postNewAdRequest(): void
    {
        $this->sendHeaders();
        $responseData = array();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $adDetails = json_decode($_POST['adDetails'], true);

            // Validate and sanitize inputs
            $username = $this->sanitizeInput($adDetails['loggedUserName']);
            $productName = $this->sanitizeInput($adDetails['productName']);
            $productPrice = $this->sanitizeInput($adDetails['price']);
            $productDescription = $this->sanitizeInput($adDetails['productDescription']);

            $image = $_FILES['image'];
            $responseData = $this->processImage($image);

            if ($responseData['success']) {
                $responseData = $this->adService->saveNewAd($productName, $productPrice, $productDescription, $image, $username);
            }

            echo json_encode($responseData);
        }
    }


    public function handleSearchRequest(): void
    {
        $this->sendHeaders();
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $ads = null;
            if (empty($_GET['name'])) {
                $ads = $this->adService->getAllAvailableAds();
            } else {
                $productName = htmlspecialchars($_GET['name']);
                $ads = $this->adService->searchAdsByProductName($productName);
            }
            echo json_encode($ads);
        }
    }

    public function handleAdEditRequest(): void
    {
        $this->sendHeaders();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $responseData = array();
            $editedAdDetails = json_decode($_POST['editedAdDetails'], true);
            $productName = htmlspecialchars($editedAdDetails['productName'], ENT_QUOTES, 'UTF-8');
            $productPrice = htmlspecialchars($editedAdDetails['price'], ENT_QUOTES, 'UTF-8');
            $productDescription = htmlspecialchars($editedAdDetails['productDescription'], ENT_QUOTES, 'UTF-8');
            $adID = htmlspecialchars($editedAdDetails["adId"], ENT_QUOTES, 'UTF-8');
            // Process the image file
            $image = $_FILES['inputImage'];
            // Validate the image file
            $responseData = $this->processImage($image);
            if ($responseData['success']) {
                error_clear_last();
                $this->adService->editAdWithNewDetails($image, $productName, $productDescription, $productPrice, $adID);
                $responseData = $this->getResponseMessage(error_get_last());
            }
            echo json_encode($responseData);
        }
    }

    public function operateAdRequest(): void
    {
        $responseData = "";
        $this->sendHeaders();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $body = file_get_contents('php://input');
            $data = json_decode($body);
            if (htmlspecialchars($data->OperationType, ENT_QUOTES, 'UTF-8') == "ChangeStatusOfAd") {
                error_clear_last();
                $this->adService->markAdAsSold(htmlspecialchars($data->adID));
                // checking if are triggered or not
                $responseData = $this->getResponseMessage(error_get_last()); // setting error according to error

            } else if (htmlspecialchars($data->OperationType) == "DeleteAd") {
                error_clear_last();
                $this->adService->deleteAd(htmlspecialchars($data->adID), htmlspecialchars($data->imageURI));
                $responseData = $this->getResponseMessage(error_get_last()); // setting error according to error
            }
            echo json_encode($responseData);
        }
    }

    public function sendAdsByLoggedUser(): void
    {
        $this->sendHeaders();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $body = file_get_contents('php://input');
            $data = json_decode($body);
            $loggedUserId = (int)$data->loggedUserId; // Cast to integer
            $user = new User();
            $user->setId($loggedUserId);
            $ads = $this->adService->getAdsByLoggedUser($user);
            echo json_encode($ads);
        }
    }


    private function createAd($name, $price, $description, $imageURI, $userID): Ad
    {
        $ad = new Ad();
        $ad->setProductName($name);
        $ad->setProductPrice($price);
        $ad->setProductDescription($description);
        $ad->getUserID()->setId($userID);
        $ad->setProductImageURI($imageURI);
        return $ad;
    }

    private function getResponseMessage($error): mixed
    {
        if ($error !== null) {
            $errorMessage = $error['message'];
            $responseData = array(
                "success" => false,
                "message" => "$errorMessage"
            );
        } else {
            $responseData = array(
                "success" => true,
                "message" => ""
            );
        }
        return $responseData;
    }

    function processImage($image)
    {
        if ($image['error'] == UPLOAD_ERR_OK) {
            $imageType = $image['type'];

            // Validate the image file
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($imageType, $allowedTypes)) {
                return array(
                    "success" => false,
                    "message" => "This type of image file are not accepted"
                );
            } else {
                return array(
                    "success" => true,
                    "message" => ""
                );
            }
        } else {
            return array(
                "success" => false,
                "message" => "Something went Wrong while uploading image"
            );
        }
    }

    private function sendHeaders(): void
    {
        header('X-Powered-By: PHP/8.1.13');
        header("Pragma: no-cache");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        header("Access-Control-Allow-Methods: *");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header('Content-Type: application/json');
    }

    private function sanitizeInput($input)
    {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }
}
