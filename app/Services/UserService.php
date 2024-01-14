<?php
require_once __DIR__ . '/../repositories/UserRepository.php';

class UserService
{
    private UserRepository $repository;


    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    public function verifyAndGetUser($email, $password)
    {
        $stmt = $this->connection->prepare("SELECT * FROM User WHERE email = :email AND password = :password");
        echo $stmt->queryString; // Add this line for debugging
        return $this->repository->verifyAndGetUser($email, $password);
    }


    /**
     * Hashes a password using Argon2i algorithm with a randomly generated salt.
     *
     * @param string $password The plain text password to be hashed.
     *
     * @return array Associative array containing 'hash' and 'salt'.
     * @throws Exception If random_bytes() fails.
     */
    public function hashPassword(string $password): string
    {
        try {
            $hashPassword = password_hash($password, PASSWORD_ARGON2I);
            if ($hashPassword === false) {
                throw new Exception('Password hash failed.');
            }
            return $hashPassword;
        } catch (Exception $exception) {
            // Handle the exception according to your application's needs.
            throw new Exception('Error hashing password: ' . $exception->getMessage());
        }
    }



    public function createNewUser($userDetails): bool
    {
        $hashPasswordWithSalt = $this->hashPassword($userDetails["password"]);
        $userDetails["hashPassword"] = $hashPasswordWithSalt[0];
        return $this->repository->insertUserInDatabase($userDetails);
    }

    public function CheckUserExistenceByEmail($email) :bool{
        return $this->repository->CheckUserEmailExistence($email);
    }
}
