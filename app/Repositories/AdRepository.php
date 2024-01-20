<?php
require_once __DIR__ . "/../Models/Ad.php";
require_once __DIR__ . "/../Models/User.php";
require_once __DIR__ . "/Repository.php";
require_once __DIR__ . "/UserRepository.php";
require_once __DIR__ . "/../Models/Status.php";

class AdRepository extends Repository
{
    private $userRepository;
    private $dbStoredName;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }

    public function getAllAdsByStatus(Status $status)
    {
        try {
            $stmt = $this->connection->prepare("SELECT id, productName, productDescription, productPrice, postedDate, productImageURI, productStatus, userID FROM Ads WHERE productStatus = :status ORDER BY postedDate DESC");
            $label = $status->label();
            $stmt->bindParam(":status", $label);
            if ($this->checkAdinDB($stmt)) {
                $stmt->execute();
                $result = $stmt->fetchAll();
                $ads = array();
                foreach ($result as $row) {
                    $ads[] = $this->makeAnAd($row);
                }
                return $ads;
            }
            return null;
        } catch (PDOException $e) {
            // Handle the exception
            trigger_error("An error occurred: " . $e->getMessage(), E_USER_ERROR);
        }
    }

    public function getAdByID($adId): Ad
    {
        try {

            $stmt = $this->connection->prepare("SELECT id,productName,productDescription,productPrice,postedDate,productImageURI,productStatus,userID,buyerID From Ads WHERE id= :adId");
            $stmt->bindValue(":adId", $adId);
            $stmt->execute();
            $row = $stmt->fetch();
            return $this->makeAnAd($row);
        } catch (PDOException  $e) {
            $message = '[' . date("F j, Y, g:i a e O") . ']' . $e->getMessage() . $e->getCode() . $e->getFile() . ' Line ' . $e->getLine() . PHP_EOL;
            error_log("Something went wrong getting ads from database " . $message, 3, __DIR__ . "/../Errors/error.log");
            http_response_code(500);
            exit();
        }
    }

    public function getAdsByLoggedUser($loggedUser)
    {
        try {
            $stmt = $this->connection->prepare("SELECT id, productName, productDescription, productPrice, postedDate, productImageURI, productStatus, userID, buyerID FROM Ads WHERE UserID = :userID ORDER BY postedDate DESC");
            $id = $loggedUser->userID; // Update this line to reflect the actual property name
            $stmt->bindParam(":userID", $id);

            if ($this->checkAdinDB($stmt)) {
                $stmt->execute();
                $result = $stmt->fetchAll();
                $ads = array();
                foreach ($result as $row) {
                    $ads[] = $this->makeAnAd($row);
                }
                return $ads;
            }
            return null;
        } catch (PDOException $e) {
            $message = '[' . date("F j, Y, g:i a e O") . ']' . $e->getMessage() . $e->getCode() . $e->getFile() . ' Line ' . $e->getLine() . PHP_EOL;
            error_log("Something went wrong getting ads from database " . $message, 3, __DIR__ . "/../Errors/error.log");
            http_response_code(500);
            exit();
        }
    }
    public function getPurchasesByLoggedInUser($loggedUser)
    {
        try {
            $stmt = $this->connection->prepare("SELECT id, productName, productDescription, productPrice, postedDate, productImageURI, productStatus, userID, buyerID FROM Ads WHERE buyerID = :userID ORDER BY postedDate DESC");
            $id = $loggedUser->userID; // Update this line to reflect the actual property name
            $stmt->bindParam(":userID", $id);

            if ($this->checkAdinDB($stmt)) {
                $stmt->execute();
                $result = $stmt->fetchAll();
                $ads = array();
                foreach ($result as $row) {
                    $ads[] = $this->makeAnAd($row);
                }
                return $ads;
            }
            return null;
        } catch (PDOException $e) {
            // Handle the exception
            trigger_error("An error occurred: " . $e->getMessage(), E_USER_ERROR);
        }
    }

    public function updateStatusOfAd($status, $adID)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE Ads SET productStatus = :productStatus WHERE id = :adId");
            $stmt->bindValue(":productStatus", $status->label());
            $stmt->bindValue(":adId", $adID);

            if ($stmt->execute()) {
                $rows_updated = $stmt->rowCount();
                if ($rows_updated <= 0) {
                    trigger_error("Ad couldn't be updated. Please try again.", E_USER_ERROR);
                }
            } else {
                trigger_error("Ad couldn't be updated.", E_USER_ERROR);
            }
        } catch (PDOException|Exception $e) {
            trigger_error("An error occurred while updating the status of ad: " . $e->getMessage(), E_USER_ERROR);
        }
    }


    public function deleteAd($adID, $imageURI)
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM Ads  WHERE id= :adId");
            $stmt->bindValue(":adId", $adID);
            if ($stmt->execute()) {
                $rows_updated = $stmt->rowCount();
                if ($rows_updated > 0) {
                    $imageFile = __DIR__ . '/../public' . $imageURI;
                    unlink($imageFile);
                } else {
                    trigger_error(" Ad couldn't be deleted", E_USER_ERROR);
                }
            } else {
                trigger_error(" Ad couldn't be Deleted", E_USER_ERROR);
            }
        } catch (PDOException|Exception   $e) {
            trigger_error("An error occurred: " . $e->getMessage(), E_USER_ERROR);
        }
    }
    private function makeAnAd($dBRow): Ad
    {
        $ad = new Ad();
        $ad->setId($dBRow["id"]);
        $ad->setProductName($dBRow["productName"]);
        $ad->setProductDescription($dBRow["productDescription"]);
        $ad->setProductPrice($dBRow["productPrice"]);
        $ad->setPostedDate($dBRow["postedDate"]);
        $ad->setProductImageURI($dBRow["productImageURI"]);
        $ad->setProductStatus(Status::from($dBRow["productStatus"]));
        $ad->setUserID($this->userRepository->getUserById($dBRow["userID"]));
        return $ad;
    }



    private function checkAdinDB($stmt): bool
    {
        try {
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            $message = '[' . date("F j, Y, g:i a e O") . ']' . $e->getMessage() . $e->getCode() . $e->getFile() . ' Line ' . $e->getLine() . PHP_EOL;
            error_log("Something went wrong getting ads from database " . $message, 3, __DIR__ . "/../Errors/error.log");
            http_response_code(500);
            exit();
        }
    }
    public function postNewAd(Ad $ad): bool
    {
        try {
            $stmt = $this->connection->prepare("INSERT INTO Ads(productName, productDescription, productPrice, productImageURI, userID) VALUES (:productName, :productDescription, :productPrice, :productImageURI, :userID)");
            $stmt->bindValue(":productName", $ad->getProductName());
            $stmt->bindValue(":productDescription", $ad->getProductDescription());
            $stmt->bindValue(":productPrice", $ad->getProductPrice());
            $stmt->bindValue(":productImageURI", $ad->getProductImageURI());
            $stmt->bindValue(":userID", $ad->getUserID()->getId());
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                return false;
            }

            return true;
        } catch (PDOException $e) {
            http_response_code(500);
            $message = '[' . date("F j, Y, g:i a e O") . ']' . $e->getMessage() . $e->getCode() . $e->getFile() . ' Line ' . $e->getLine() . PHP_EOL;
            error_log("Something went wrong getting ads from the database " . $message, 3, __DIR__ . "/../Errors/error.log");
            exit();
        }
    }


    private function getCurrentImageUriByAdId($adId)
    {
        try {
            $stmt = $this->connection->prepare("SELECT productImageURI FROM Ads WHERE id= :adId");
            $stmt->bindValue(":adId", $adId);
            $stmt->execute();
            if ($stmt->rowCount() == 1) {
                // Statement returned exactly one row
                $result = $stmt->fetch();
                $imageURI = $result['productImageURI'];
            } else {
                // Statement returned no rows or more than one row
                throw new PDOException("someTHING WENT WRONG");
            }
            return $imageURI;
        } catch (PDOException $e) {
            trigger_error("An error occurred: " . $e->getMessage(), E_USER_ERROR);
        }
    }
    public function editAd($newImage, string $productName, string $productDescription, float $productPrice, int $adID)
    {
        try {
            if (!isset($this->dbStoredName)) {
                $this->dbStoredName = $this->getCurrentImageUriByAdId($adID);
            }

            $storingImageUri = $this->editImageFile($this->dbStoredName, $newImage);

            if (is_null($storingImageUri)) {
                throw new Exception("Something went wrong while updating the image, please try again!");
            }

            $stmt = $this->connection->prepare("UPDATE Ads SET productName = :productName, productDescription = :description, productPrice = :price, productImageURI = :imageURI WHERE id = :id");
            $stmt->bindValue(":productName", $productName);
            $stmt->bindValue(":description", $productDescription);
            $stmt->bindValue(":price", $productPrice);
            $stmt->bindValue(":imageURI", $storingImageUri);
            $stmt->bindValue(":id", $adID);
            $stmt->execute();
        } catch (PDOException | Exception $e) {
            // Provide a more detailed error message or log the error
            trigger_error("An error occurred: " . $e->getMessage(), E_USER_ERROR);
        }
    }
    public function searchAdsByProductName($productName): void
    {
        try {
            $stmt = $this->connection->prepare("SELECT id, productName, productDescription, productPrice, postedDate, productImageURI, productStatus, userID, buyerID FROM Ads WHERE `productName` LIKE :productName AND productStatus = :status");
            $stmt->bindValue(":productName", '%' . $productName . '%');
            $stmt->bindValue(":status", Status::Available->label());
            $stmt->execute();

            $result = $stmt->fetchAll();
            $ads = array();

            foreach ($result as $row) {
                $ads[] = $this->MakeAnAD($row);
            }

            echo json_encode(['ads' => $ads]);
        } catch (PDOException | Exception $e) {
            // Log the error using your existing ErrorLog class or mechanism
            $this->errorLog->logError('An error occurred: ' . $e->getMessage());

            // Return a generic error message
            echo json_encode(['error' => true, 'message' => 'An error occurred.']);
        }
    }

    private function editImageFile(string $dbStoredImageName, array $newImage)
    {
        try {
            $imageTempName = $newImage['tmp_name'];
            $newImageName = $newImage['name'];
            $newImageArray = explode('.', $newImageName);
            $newImageExtension = end($newImageArray);
            $storedImageName = explode('.', $dbStoredImageName);
            $dbStoredNameWithoutExtension = reset($storedImageName);
            $targetDirectory = __DIR__ . '/../public';

            if (unlink($targetDirectory . $dbStoredImageName)) {
                $newFileName = $dbStoredNameWithoutExtension . '.' . $newImageExtension;
                if (!move_uploaded_file($imageTempName, $targetDirectory . $newFileName)) {
                    throw new Exception("Error occurred while moving the file");
                }
                return $newFileName;
            }

            return null;
        } catch (Exception $e) {
            trigger_error("An error occurred: " . $e->getMessage(), E_USER_ERROR);
        }
    }
    public function processPurchase($adID, $loggedInUser): bool
    {
        try {
            $buyerID = (!is_null($loggedInUser)) ? $loggedInUser->getId() : null;

            $stmt = $this->connection->prepare("UPDATE Ads SET buyerID = :buyerID, productStatus = :status WHERE id = :adID");
            $stmt->bindValue(":buyerID", $buyerID, PDO::PARAM_INT);  // Assuming buyerID is an integer
            $stmt->bindValue(":status", Status::Sold->label());  // Assuming "Sold" is the appropriate status
            $stmt->bindValue(":adID", $adID, PDO::PARAM_INT);  // Assuming adID is an integer

            return $stmt->execute();
        } catch (PDOException $e) {
            // Log or handle the exception
            trigger_error("An error occurred: " . $e->getMessage(), E_USER_ERROR);
            return false;
        }
    }


}
