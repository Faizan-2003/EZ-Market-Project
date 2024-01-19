<?php
require_once __DIR__ . '/../Models/User.php';

function logOutFromApp(): void
{
    unset($_SESSION["loggedUser"]);
}
function getLoggedUser() {
    session_start();

    // Check if the user is logged in
    if (isset($_SESSION['loggedUser'])) {
        return $_SESSION['loggedUser'];
    } else {
        return null;
    }
}

function assignLoggedUserToSession(User $user): void
{
    session_start();
    $_SESSION['loggedUser'] = $user;
}
