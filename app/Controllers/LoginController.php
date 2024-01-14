<?php

require __DIR__ . '/../Services/UserService.php';
require __DIR__ . '/../Logic/LoggingInAndOut.php';


class LoginController
{
    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function displayLoginPage(): void
    {
        if (is_null(getLoggedUser())) {
            require __DIR__ . "/../Views/LoginPage/Login.php";
            require __DIR__ . '/../Views/LoginPage/LoginError.php';
            $this->loginToApp();

        } else {
            echo "<script>alert('You are already logged in. You don't have to log in again.')</script>";
            echo "<script>location.href = '/home/myAds'</script>";
            exit();
        }
    }


    private function loginToApp(): void
    {

        if (!empty($_POST["btnLogin"]) && !empty($_POST["email"]) && !empty($_POST["password"])) {
            $email = htmlspecialchars($_POST["email"]);
            $password = htmlspecialchars($_POST["password"]);
            error_log("Email: $email, Password: $password"); // Log to a file
            $loggingUser = $this->userService->verifyAndGetUser($email, $password);

            if (is_null($loggingUser)) {
                echo '<script> showLoginFailed() </script>';
            } else {
                assignLoggedUserToSession($loggingUser);
                echo "<script>location.href = '/homepage/myAds';</script>";
                exit();
            }
        }
    }

}