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

        // Respond to a POST request to /api/article
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!isset($_POST['adDetails'])) {
                // Handle the case when 'adDetails' is not set
                $responseData = array(
                    "success" => false,
                    "message" => "'adDetails' is not set in the POST data"
                );
            } else {
                $adDetails = isset($_POST['adDetails']) ? json_decode($_POST['adDetails'], true) : array();
                if ($adDetails === null) {
                    $responseData = array(
                        "success" => false,
                        "message" => "Unable to decode 'adDetails' as JSON"
                    );
                } else {
                    if (isset($adDetails['loggedUserName'], $adDetails['productName'], $adDetails['price'], $adDetails['productDescription'], $adDetails['loggedUserId'])) {
                        $username = htmlspecialchars($adDetails['loggedUserName']);
                        $productName = htmlspecialchars($adDetails['productName']);
                        $productPrice = htmlspecialchars($adDetails['price']);
                        $productDescription = htmlspecialchars($adDetails['productDescription']);

                        // Process the image file
                        $image = $_FILES['image'];
                        $responseData = $this->processImage($image);

                        if ($responseData['success']) {
                            $imageTempName = $image['tmp_name'];
                            $imageName = $image['name'];
                            $targetDirectory = "images/";
                            $imageExtension = explode('.', $imageName);
                            $uniqueIdentifier = $productName;
                            $newImageName = "EZM" . "-" . date("Y-m-d") . "-" . $username . "." .$uniqueIdentifier."-" . end($imageExtension);

                            // when everything is correct
                            $checkInDb = $this->adService->postNewAd($this->createAd($productName, $productPrice, $productDescription, "/" . $targetDirectory . $newImageName, $adDetails['loggedUserId']));

                            if ($checkInDb) {
                                $uploadedFile = move_uploaded_file($imageTempName, $targetDirectory . $newImageName);

                                if (!$uploadedFile) {
                                    $responseData = array(
                                        "success" => false,
                                        "message" => "Something went Wrong while processing your uploaded image"
                                    );
                                }
                            } else {
                                $responseData = array(
                                    "success" => false,
                                    "message" => "Something went Wrong while processing your Add request"
                                );
                            }
                        }
                    } else {
                        // Handle the case when the required keys are not present
                        $responseData = array(
                            "success" => false,
                            "message" => "Required keys are not present in 'adDetails'"
                        );
                    }
                }
            }

            // Convert the response message to a JSON string
            $responseJson = json_encode($responseData);

            // Send the response message as the body of the HTTP response
            echo $responseJson;
        }
    }

    public function createAd($productName, $productPrice, $productDescription, $productImageURI, $userID): Ad
    {
        $ad = new Ad();
        $ad->setProductName($productName);

        // Check if $productPrice is a non-empty string and convert it to float
        if (!empty($productPrice)) {
            $productPrice = (float)$productPrice;
            $ad->setProductPrice($productPrice);
        }

        $ad->setProductDescription($productDescription);
        $ad->setProductImageURI($productImageURI);

        // Check if $userID is a User object
        if ($userID instanceof User) {
            $ad->setUserID($userID);
        } else {
            // Handle the case when $userID is not a User object
            // You might need to create a User object and set its ID
            $user = new User();
            $user->setId($userID);
            $ad->setUserID($user);
        }

        return $ad;
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
    public function sendPurchasedAdsByLoggedUser($loggedUser): void
    {
        $this->sendHeaders();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $body = file_get_contents('php://input');
            $data = json_decode($body);
            $loggedUserId = (int)$data->loggedUserId; // Cast to integer

            // Fetch the logged user (you might need to adapt this based on your authentication system)
            $user = new User();
            $user->setId($loggedUserId);

            // Get purchased ads for the logged user
            $purchasedAds = $this->adService->getPurchasesByLoggedInUser($user);

            if ($purchasedAds === false) {
                // Log an error or handle it appropriately
                error_log('Failed to fetch purchased ads.');
                echo json_encode(['error' => 'Failed to fetch purchased ads.']);
            } else {
                echo json_encode($purchasedAds);
            }
        }
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
}
