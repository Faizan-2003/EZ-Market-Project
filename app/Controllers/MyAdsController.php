<?php

class MyAdsController
{
    public function displayMyAdsPage()
    {
        $ads = $this->getAdsByLoggedUser();
        require __DIR__ . '/../Views/MyAds.php';
    }

    private function getAdsByLoggedUser()
    {
        $ads = [];
        $ads = Ads::getAdsByLoggedUser();
        return $ads;
    }

    public function deleteAd()
    {
        $adId = $_POST['adId'];
        $ad = Ads::getAdById($adId);
        $ad->deleteAd();
        header('Location: /myads');
    }

    public function editAd()
    {
        $adId = $_POST['adId'];
        $ad = Ads::getAdById($adId);
        $ad->editAd();
        header('Location: /myads');
    }

    public function addAd()
    {
        $ad = new Ads();
        $ad->addAd();
        header('Location: /myads');
    }

}