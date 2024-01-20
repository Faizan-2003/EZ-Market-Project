<?php

require __DIR__ . '/../repositories/AdRepository.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . "/../Models/Ad.php";

class AdService
{
    private AdRepository $adRepository;

    public function __construct(AdRepository $adRepository)
    {
        $this->adRepository = $adRepository;
    }

    public function getAllAdsByStatus(Status $status): array
    {
        return $this->adRepository->getAllAdsByStatus($status);
    }

    public function searchAdsByProductName($productName)
    {
        return $this->adRepository->searchAdsByProductName($productName);
    }

    public function getAdByID(int $adId): ?Ad
    {
        return $this->adRepository->getAdByID($adId);
    }

    public function getAllAvailableAds(): array
    {
        return $this->getAllAdsByStatus(Status::Available);
    }

    public function getAdsByLoggedUser(User $loggedUser): array
    {
        $ads = $this->adRepository->getAdsByLoggedUser($loggedUser);
        return $ads ?: []; // Return an empty array if $ads is null
    }
    public function getPurchasesByLoggedInUser(User $loggedUser): array
    {
        $ads = $this->adRepository->getPurchasesByLoggedInUser($loggedUser);
        return $ads ?: [];
    }

    public function postNewAd(Ad $ad): bool
    {
        try {
            return $this->adRepository->postNewAd($ad);
        } catch (PDOException $e) {
            // Log or handle the exception
            return false;
        }
    }

    public function updateStatusOfAd(Status $status, int $adID): void
    {
        $this->adRepository->updateStatusOfAd($status, $adID);
    }

    public function deleteAd(int $adID, string $imageFile): void
    {
        $this->adRepository->deleteAd($adID, $imageFile);
    }

    public function markAdAsSold(int $adId): void
    {
        $this->updateStatusOfAd(Status::Sold, $adId);
    }

    public function editAdWithNewDetails(array $newImage, string $productName, string $description, float $price, int $adID): void
    {
        $this->adRepository->editAd($newImage, $productName, $description, $price, $adID);
    }
    // In AdService class
    public function processPurchaseBuyer(User $loggedInUser, $total): array
    {
        $adIDs = []; // Initialize an array to store the ad IDs

        // Iterate over the items in the shopping cart
        foreach (getItemsInShoppingCart() as $item) {
            $adID = $item->getId();

            // Update the ad status and set the buyerID
            $this->adRepository->processPurchase($adID, $loggedInUser);

            // Store the ad ID in the array
            $adIDs[] = $adID;
        }

        // Additional logic related to the purchase, if needed

        return $adIDs; // Return the array of ad IDs
    }


}
