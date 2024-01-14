<?php
require_once __DIR__ . '/../Models/User.php';
include_once __DIR__ . '/Repository.php';

class UserRepository extends Repository
{
    function verifyAndGetUser($email, $enteredPassword)
    {
        $stmt = $this->connection->prepare("SELECT userID, firstName, lastName, email, password FROM User WHERE email = :email");

        try {
            $user = null;
            $stmt->bindParam(":email", $email);
            $stmt->execute();

            if ($this->checkUserExistence($stmt)) {
                $storedPassword = $this->getHashedPassword($stmt);

                if ($this->verifyPassword($enteredPassword, $storedPassword[0])) {
                    $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
                    $user = $stmt->fetch();
                }
            }

            return $user;
        } catch (PDOException $e) {
            // Handle the exception according to your application's needs.
            $message = '[' . date("F j, Y, g:i a e O") . ']' . $e->getMessage() . $e->getCode() . $e->getFile() . ' Line ' . $e->getLine() . PHP_EOL;
            $errorLogPath = __DIR__ . "/../Errors/error.log";
            error_log("Database connection failed: " . $message, 3, $errorLogPath);
            exit();
        }
    }


    private function getHashedPassword($stmt)
    {
        try {
            $stmt->execute();

            while ($result = $stmt->fetch(PDO::FETCH_BOTH)) {
                $hashPassword = $result["password"];
            }
            return [$hashPassword];
        } catch (PDOException $e) {
            $message = '[' . date("F j, Y, g:i a e O") . ']' . $e->getMessage() . $e->getCode() . $e->getFile() . ' Line ' . $e->getLine() . PHP_EOL;
            $errorLogPath = __DIR__ . '/../Errors/error.log';
            error_log("Database connection failed: " . $message, 3, $errorLogPath);
            exit();
        }
    }

    private function checkUserExistence($stmt): bool
    {
        try {
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            $message = '[' . date("F j, Y, g:i a e O") . ']' . $e->getMessage() . $e->getCode() . $e->getFile() . ' Line ' . $e->getLine() . PHP_EOL;
            $errorLogPath = __DIR__ . '/../Errors/error.log';
            error_log("Database connection failed: " . $message, 3, $errorLogPath);
            exit();
        }
    }

    private function verifyPassword($enteredPassword, $hashedPassword): bool
    {
        return password_verify($enteredPassword, $hashedPassword);
    }


    public function getUserById($id)
    {
        try {
            $stmt = $this->connection->prepare("SELECT userID, firstName, lastName, email FROM User WHERE userID = :id");
            $stmt->bindParam(":id", $id); // Corrected parameter name here
            if ($this->checkUserExistence($stmt)) {
                $stmt->execute();
                $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
                return $stmt->fetch();
            }
            return null;
        } catch (PDOException $e) {
            // Handle the exception according to your application's needs.
            $message = '[' . date("F j, Y, g:i a e O") . ']' . $e->getMessage() . $e->getCode() . $e->getFile() . ' Line ' . $e->getLine() . PHP_EOL;
            $errorLogPath = __DIR__ . '/../Errors/error.log';
            error_log("Database connection failed: " . $message, 3, $errorLogPath);
            exit();
        }
    }

    public function insertUserInDatabase($userDetails): bool
    {
        try {
            $stmt = $this->connection->prepare("INSERT INTO User(firstName, lastName, email, password) VALUES (:firstName, :lastName, :email, :password)");
            $stmt->bindValue(":firstName", $userDetails["firstName"]);
            $stmt->bindValue(":lastName", $userDetails["lastName"]);
            $stmt->bindValue(":email", $userDetails["email"]);
            $stmt->bindValue(":password", $userDetails["password"]);
            $stmt->execute();
            if ($stmt->rowCount() == 0) {
                return false;
            }
            return true;
        } catch (PDOException $e) {
            $message = '[' . date("F j, Y, g:i a e O") . ']' . $e->getMessage() . $e->getCode() . $e->getFile() . ' Line ' . $e->getLine() . PHP_EOL;
            $errorLogPath = __DIR__ . '/../Errors/error.log';
            error_log("Database connection failed: " . $message, 3, $errorLogPath);
            exit();
        }
    }

    public function CheckUserEmailExistence($email): bool
    {
        try {
            $stmt = $this->connection->prepare("SELECT email FROM User WHERE email = :email");
            $stmt->bindValue(":email", $email);
            return $this->checkUserExistence($stmt);
        } catch (PDOException $e) {
            $message = '[' . date("F j, Y, g:i a e O") . ']' . $e->getMessage() . $e->getCode() . $e->getFile() . ' Line ' . $e->getLine() . PHP_EOL;
            $errorLogPath = __DIR__ . '/../Errors/error.log';
            error_log("Database connection failed: " . $message, 3, $errorLogPath);
            exit();
        }
    }
}
