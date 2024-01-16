<?php
require_once __DIR__ . '/../repositories/UserRepository.php';

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function verifyAndGetUser($email, $password)
    {
        return $this->userRepository->verifyAndGetUser($email, $password);
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
            // Log the error, display a user-friendly message, etc.
            error_log('Error hashing password: ' . $exception->getMessage());
            // Re-throw the exception if you want to propagate it further.
            throw $exception;
        }
    }


    public function createNewUser($userDetails): bool
    {
        $hashPasswordWithSalt = $this->hashPassword($userDetails["password"]);
        $userDetails["hashPassword"] = $hashPasswordWithSalt; // Fix here
        return $this->userRepository->insertUserInDatabase($userDetails); // Fix here
    }

    public function CheckUserExistenceByEmail($email): bool
    {
        return $this->userRepository->CheckUserEmailExistence($email); // Fix here
    }
}
