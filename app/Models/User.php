<?php
class User implements jsonSerializable
{
    public $userID;
    public string $firstName;
    public string $lastName;
    public string $email;
    private $password;
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->userID;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->userID = $id;
    }


    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }


    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
