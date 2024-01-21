<?php
require __DIR__.'/../Services/UserService.php';
Class RegisterUserController{
    private UserService $userService;

    public function __construct()
    {
        $userRepository = new UserRepository();
        $this->userService = new UserService($userRepository);
    }
    public function displayRegisterUserPage():void{

        require __DIR__.'/../Views/RegisterNewUser/RegisterNewUser.html';
        require __DIR__ . '/../Views/Footer.php';

        $this->createUser();
    }
    private function createUser() :void{
        if(isset($_POST["btnRegister"])){
            if($this->userService->CheckUserExistenceByEmail(htmlspecialchars($_POST["email"]))){
                echo"<script>displayModalForSignUp('Ohoooo!','The email address you entered is already taken😔, Please choose another email address!')</script>";
                return;
            }
            $userDetails= array(
                "firstName" =>htmlspecialchars($_POST["FirstName"]),
                "lastName" =>htmlspecialchars($_POST["LastName"]),
                "email" => htmlspecialchars($_POST["email"]),
                "password" =>htmlspecialchars($_POST["password"])
            );
            if($this->userService->createNewUser($userDetails)){
                echo"<script>displayModalForSignUp('Hurrahh!',' Your account has been created successfully. You can now log in.')</script>";
            }
            else{
                echo"<script>displayModalForSignUp('Ohoooo!','Something went wrong while creating your account. please, Try Again!')</script>";
            }
        }
    }
}