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
            error_log('Error hashing password: ' . $exception->getMessage());
            throw $exception;
        }
    }
    public function createNewUser($userDetails): bool
    {
        $hashedPassword = password_hash($userDetails["password"], PASSWORD_BCRYPT);
        $userDetails["hashPassword"] = $hashedPassword;
        return $this->userRepository->insertUserInDatabase($userDetails);
    }
    public function CheckUserExistenceByEmail($email): bool
    {
        return $this->userRepository->CheckUserEmailExistence($email);
    }
}
