<?php
require_once __DIR__ . "/User.php";
require_once __DIR__ . "/Status.php";

class Ad implements jsonSerializable
{
    private int $id;
    private string $productName;
    private string $productDescription;
    private string $postedDate;
    private float $productPrice;
    private string $productImageURI;
    private Status $productStatus;
    private User $userID;
    private User $BuyerID;

    public function __construct()
    {
        $this->userID = new User();
    }

    /**
     * @return Status
     */
    public function getProductStatus(): Status
    {
        return $this->productStatus;
    }

    /**
     * @param Status $productStatus
     */
    public function setProductStatus(Status $productStatus): void
    {
        $this->productStatus = $productStatus;
    }

    /**
     * @return string
     */
    public function getProductImageURI(): string
    {
        return $this->productImageURI;
    }

    /**
     * @param string $productImageURI
     */
    public function setProductImageURI(string $productImageURI): void
    {
        $this->productImageURI = $productImageURI;
    }

    /**
     * @return User
     */
    public function getUserID(): User
    {
        return $this->userID;
    }

    /**
     * @param User $userID
     */
    public function setUserID(User $userID): void
    {
        $this->userID = $userID;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getProductName(): string
    {
        return $this->productName;
    }

    /**
     * @param string $productName
     */
    public function setProductName(string $productName): void
    {
        $this->productName = $productName;
    }

    /**
     * @return string
     */
    public function getProductDescription(): string
    {
        return $this->productDescription;
    }

    /**
     * @param string $productDescription
     */
    public function setProductDescription(string $productDescription): void
    {
        $this->productDescription = $productDescription;
    }

    /**
     * @return String
     */
    public function getPostedDate(): string
    {
        return $this->postedDate;
    }

    /**
     * @param String $postedDate
     */
    public function setPostedDate(string $postedDate): void
    {
        $this->postedDate = $postedDate;
    }

    /**
     * @return float
     */
    public function getProductPrice(): float
    {
        return $this->productPrice;
    }

    /**
     * @param float $productPrice
     */
    public function setProductPrice(float $productPrice): void
    {
        $this->productPrice = $productPrice;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }

    public function __equals(Ad $other): bool
    {
        return $this->id === $other->id;
    }
}
