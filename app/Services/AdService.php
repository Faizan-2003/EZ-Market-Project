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

    public function searchAdsByProductName(string $productName): array
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
        return $this->adRepository->getAdsByLoggedUser($loggedUser);
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
}
