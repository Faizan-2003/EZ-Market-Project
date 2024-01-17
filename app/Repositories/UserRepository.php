<?php
require_once __DIR__ . '/../Models/User.php';
include_once __DIR__ . '/Repository.php';

class UserRepository extends Repository
{
    function verifyAndGetUser($email, $enteredPassword)
    {
        try {
            $user = null;
            $stmt = $this->connection->prepare("SELECT userID, firstName, lastName, email, password FROM User WHERE email= :email");
            $stmt->bindParam(":email", $email);

            if ($this->checkUserExistence($stmt)) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $storedPassword = $result["password"];

                if ($this->verifyPassword($enteredPassword, $storedPassword)) {
                    $stmt->execute();
                    $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
                    $user = $stmt->fetch();
                }
            }

            return $user;
        } catch (PDOException $e) {
            $this->handleError($e);
        }
    }

    private function checkUserExistence($stmt): bool
    {
        try {
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            $this->handleError($e);
        }
        return  false;
    }

    private function verifyPassword($enteredPassword, $hashedPassword): bool
    {
        return password_verify($enteredPassword, $hashedPassword);
    }

    public function getUserById($id)
    {
        try {
            $stmt = $this->connection->prepare("SELECT userID, firstName, lastName, email FROM User WHERE userID = :id");
            $stmt->bindParam(":id", $id);
            if ($this->checkUserExistence($stmt)) {
                $stmt->execute();
                $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
                return $stmt->fetch();
            }
            return null;
        } catch (PDOException $e) {
            $this->handleError($e);
        }
    }

    public function insertUserInDatabase($userDetails): bool
    {
        try {
            // Hash the password before storing it in the database
            $hashedPassword = password_hash($userDetails["password"], PASSWORD_DEFAULT);

            $stmt = $this->connection->prepare("INSERT INTO User(firstName, lastName, email, password) VALUES (:firstName, :lastName, :email, :password)");
            $stmt->bindValue(":firstName", $userDetails["firstName"]);
            $stmt->bindValue(":lastName", $userDetails["lastName"]);
            $stmt->bindValue(":email", $userDetails["email"]);
            $stmt->bindValue(":password", $hashedPassword); // Store the hashed password
            $stmt->execute();

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            $this->handleError($e);
        }
        return false;
    }


    public function CheckUserEmailExistence($email): bool
    {
        try {
            $stmt = $this->connection->prepare("SELECT email FROM User WHERE email = :email");
            $stmt->bindValue(":email", $email);
            return $this->checkUserExistence($stmt);
        } catch (PDOException $e) {
            $this->handleError($e);
        }
        return false;
    }

    private function handleError(PDOException $e)
    {
        $message = '[' . date("F j, Y, g:i a e O") . ']' . $e->getMessage() . $e->getCode() . $e->getFile() . ' Line ' . $e->getLine() . PHP_EOL;
        error_log("Database connection failed: " . $message, 3, __DIR__ . "/../Errors/error.log");
        http_response_code(500);
        exit();
    }
}

?>
