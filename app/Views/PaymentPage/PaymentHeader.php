<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payment - EZ Market</title>
    <link rel="icon" type="image/x-icon" href="/images/favicon.svg">
    <style> <?php include __DIR__ . '/../../public/CSS/WebsiteStyle.css'; ?> </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/22097c36aa.js" crossorigin="anonymous"></script>
</head>

<body class="d-flex flex-column h-100">
<nav class="navbar navbar-expand-lg navbar-dark bg-info p-3 custom-navbar">
    <div class="container-fluid">
        <a class="navbar-brand"><img src="/images/Logo.png" alt="WebsiteLogo"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link mx-3" href="/homepage">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-3" href="/homepage/myAds">My Ads</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-3" href="/homepage/mypurchases">My Purchases</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link mx-2 active-page selected" href="/homepage/shoppingCart">
                        <i class="fa badge fa-lg" style="font-size:1.5em" data-value="<?= isset($_SESSION['cartItems']) ? count($_SESSION['cartItems']) : 0; ?>">&#xf07a;</i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-2" href="/homepage/login" style="border: 1px solid #000080; padding: 10px 15px; border-radius: 5px;">Log In</a>
                </li>
            </ul>
        </div>
    </div>
</nav>