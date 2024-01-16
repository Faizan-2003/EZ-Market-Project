<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>EZ market - Login</title>
    <link rel="icon" type="image/x-icon" href="/images/favicon.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <style>
        <?php include __DIR__ . '/../../public/CSS/LoginStyles.css'; ?>
    </style>
</head>

<body>
<main class="form-signin w-100 m-auto" id="loginForm">
    <form method="POST" >
        <a href="/homepage"><img class="mb-4" src="/images/Logo.png" alt="Website Logo" width="350" height="150"></a>
        <h1 class="h3 mb-3 fw-bold">Log In</h1>
        <div class="form-floating">
            <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="email" autocomplete="email" required>
            <label for="floatingInput">Email address</label>
        </div>
        <div class="form-floating" id="passwordDiv">
            <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password" autocomplete="current-password" required>
            <i class="fa fa-eye" data-bs-toggle="password-toggle" data-bs-target="#password"></i>
            <label for="floatingPassword">Password</label>
        </div>
        <div class="p"><button class="w-100 btn btn-lg btn-success" type="submit" name="btnLogin" style="background-color: #000080;">Log in</button></div>

        <div class="text-center pt-3">
            <p class="text-black">Not a member?<a href="/homepage/login/signup"> Register</a></p>
        </div>
        <p class="mt-5 mb-3 text-black copyRight"> &#169;EZmarket.com</p>

