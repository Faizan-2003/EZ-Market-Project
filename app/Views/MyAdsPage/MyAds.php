<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My Ads - EZ Market</title>
    <link rel="icon" type="image/x-icon" href="/images/favicon.svg">
    <style>
        <?php include __DIR__ . '/../../public/CSS/WebsiteStyle.css'; ?>
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/22097c36aa.js" crossorigin="anonymous"></script>
</head>

<body class="d-flex flex-column h-100">
<nav class="navbar navbar-expand-lg navbar-dark bg-info p-3 custom-navbar">
    <div class="container-fluid">
        <a class="navbar-brand"><img src="/images/Logo.png" alt="WebsiteLogo"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link mx-3" href="/homepage">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-3 active-page" aria-current="page" href="/homepage/myAds">My Ads</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-3" href="/homepage/mypurchases">My Purchases</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link mx-2" href="/homepage/shoppingCart">
                        <i class="fa badge fa-lg" style="font-size:1.5em" data-value="<?= isset($_SESSION['cartItems']) ? count($_SESSION['cartItems']) : 0; ?>">&#xf07a;</i>
                    </a>
                <li class="nav-item">
                    <a class="nav-link mx-2 login" href="/homepage/login" style="border: 1px solid #000080; padding: 10px 15px; border-radius: 5px;">Log In</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-2 logout"  href="/homepage/login" style="border: 1px solid #000080; padding: 10px 15px; border-radius: 5px;">Log Out</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid px-3 py-2 my-2 text-center" style="background-color: #000080;">
    <?php if (isset($displayMessage)): ?>
        <h1 id="displayMessage" class="display-6 fw-semibold " style="color: white"><?= $displayMessage ?></h1>
    <?php endif; ?>
    <div class="col-lg-6 mx-auto" >
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center" id="buttonHolder" >
            <button id="buttonPostNewAd" style="color: #ffb703" type="button" class="btn btn-lg px-4 gap-3" data-bs-toggle="modal" data-bs-target="#myModal">
                <i class="fa-solid fa-pen-to-square" ></i> Post New Ad
            </button>
        </div>
    </div>
</div>

<div>
    <?php if (!is_null($this->loggedUser)) { ?>
        <input type="hidden" id="hiddenLoggedUserId" value="<?= $this->loggedUser->getId() ?>">
        <input type="hidden" id="loggedUserName" value="<?= $this->loggedUser->getFirstName() ?>">
    <?php } ?>
</div>
<!-- The Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="postNewAddForm">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Add New Post</h4>
                    <button id="close" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group">
                        <label for="productName"><strong>Product Name</strong></label>
                        <input type="text" class="form-control" id="productName" placeholder="Enter product name" required>
                    </div>
                    <div class="form-group">
                        <label for="image"><strong>Image</strong></label><br>
                        <input type="file" class="form-control-file" id="image" accept="image/png, image/jpeg,image/jpg" ondragover="allowDrop(event)" ondrop="dropFile(event)" required style="   .dragover {border: 2px dashed #000;}">
                    </div>
                    <div class="form-group">
                        <label for="price"><strong>Price</strong></label>
                        <input type="number" class="form-control" id="price" placeholder="Set Your Product Price" required>
                    </div>
                    <div class="form-group">
                        <label for="productDescription"><strong>Product Description</strong></label>
                        <textarea class="form-control" id="productDescription" rows="3" placeholder="Describe about your product like how long it is used for,what's the brand of the product" required></textarea>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="resetPostNewAddForm()">Cancel
                    </button>
                    <button type="submit" class="btn btn-success" id="btnPostNewAdd" onclick="postNewAdd()">Post
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

