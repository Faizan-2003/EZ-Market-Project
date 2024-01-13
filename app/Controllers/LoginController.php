<?php

require __DIR__ . '/../Services/UserService.php';
require __DIR__ . '/../Logic/LoggingInAndOut.php';
require __DIR__ . "/../Views/LoginPage/LoginError.php";

class LoginController
{
    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function displayLoginPage(): void
    {
        if(is_null(getLoggedUser())){
            require __DIR__ . '/../Views/LoginPage/Login.php';
            require __DIR__ . '/../Views/LoginPage/LoginError.php';
            $this->loginToApp();
        } else {
            echo "<Script>alert('You are already logged in.')</Script>";
            echo "<script>location.href = '/home/myAds'</script>";
            exit();
        }
    }

    private function loginToApp(): void
    {
        if (isset($_POST["btnLogin"])) {
            $email = htmlspecialchars($_POST["email"]);
            $password = htmlspecialchars($_POST["password"]);
            $loggingUser = $this->userService->verifyAndGetUser($email, $password);
            if (is_null($loggingUser)) {
                echo ' <Script> showLoginFailed()</Script>';
            } else {
                assignLoggedUserToSession($loggingUser);
                echo "<script>location.href = '/homepage/myAds'</script>";
                exit();
            }
        }
    }

}