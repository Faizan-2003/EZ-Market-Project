<?php
require_once __DIR__ . '/../repositories/UserRepository.php';

class UserService
{
    private $repository;


    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    public function verifyAndGetUser($email, $password)
    {
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
    public function hashPassword(string $password): array
    {
        try {
            $salt = bin2hex(random_bytes(32));
            $hashPassword = password_hash($password . $salt, PASSWORD_ARGON2I);

            return [
                'hash' => $hashPassword,
                'salt' => $salt,
            ];
        } catch (Exception $exception) {
            // Log the error or handle it based on your application's needs.
            // Avoid echoing error messages directly in production.
            // You might want to throw a custom exception or return a default value.
            throw new Exception('Error hashing password: ' . $exception->getMessage());
        }
    }


    public function createNewUser($userDetails): bool
    {
        $hashPasswordWithSalt = $this->hashPassword($userDetails["password"]);
        $userDetails["hashPassword"] = $hashPasswordWithSalt[0];
        $userDetails["salt"] = $hashPasswordWithSalt[1];
        return $this->repository->insertUserInDatabase($userDetails);
    }

    public function CheckUserExistenceByEmail($email) :bool{
        return $this->repository->CheckUserEmailExistence($email);
    }
}
