function resetPostNewAdForm() {
    document.getElementById("postNewAdForm").reset();
}

function hidePostNewAd() {
    document.getElementById("buttonPostNewAd").hidden = true;
    const divCol = document.getElementById("buttonHolder");
    const a = document.createElement("a");
    a.href = "login";
    a.className = "btn btn-success btn-lg px-4 gap-3";
    a.innerHTML = "Log In";
    divCol.appendChild(a);

}

function showPostNewAd() {
    document.getElementById("buttonPostNewAd").hidden = false;
}

function postNewAdd() {
    console.log('postNewAdd function called');

    const inputLoggedUserId = escapeHtml(document.getElementById("hiddenLoggedUserId").value);
    const inputLoggedUserName = escapeHtml(document.getElementById("loggedUserName").value);
    const inputProductName = escapeHtml(document.getElementById("productName").value);
    const inputPrice = escapeHtml(document.getElementById("price").value);
    const inputProductDescription = escapeHtml(document.getElementById("productDescription").value);
    const inputProductImage = document.getElementById("image").files[0];

    if (!validateForm(inputProductName, inputPrice, inputProductDescription, inputProductImage)) {
        return;
    }

    const adDetails = {
        loggedUserId: inputLoggedUserId,
        loggedUserName: inputLoggedUserName,
        productName: inputProductName,
        price: inputPrice,
        productDescription: inputProductDescription,
    };

    const formData = new FormData();
    formData.append("image", inputProductImage);
    formData.append("adDetails", JSON.stringify(adDetails));

    sendRequestForInsertingAd(formData);
}

function sendRequestForInsertingAd(formData) {
    event.preventDefault();
    fetch('http://localhost/api/adsapi', {
        method: 'POST',
        body: formData,
    })
        .then(function (response) {
            return response.json();
        })
        .then(function (responseData) {
            if (responseData.success) {
                document.getElementById("close").click();
                resetPostNewAdForm();  // clearing the fields of the form
                loadAdsOfLoggedUser();
            } else {
                alert(responseData.message);
            }
        })
        .catch(err => console.error(err));
}

function createHorizontalAdCard(ad) {
    let card = document.createElement("div");
    card.classList.add("card", "mb-3");
    card.style.maxWidth = "900px";
    card.style.position = "relative";

    let row = document.createElement("div");
    row.classList.add("row", "g-0");
    card.appendChild(row);

    let imageCol = document.createElement("div");
    imageCol.classList.add("col-md-4", "col-xl-4");
    row.appendChild(imageCol);

    let image = document.createElement("img");
    image.src = ad.productIamageURI;
    image.classList.add("img-fluid", "rounded-start");
    imageCol.appendChild(image);

    let detailsCol = document.createElement("div");
    detailsCol.classList.add("col-md-8", "col-xl-8", "d-flex", "flex-column", "justify-content-around", "align-items-center", "h-100");
    row.appendChild(detailsCol);

    let detailsBody = document.createElement("div");
    detailsBody.classList.add("card-body");
    detailsCol.appendChild(detailsBody);

    let productName = document.createElement("h5");
    productName.classList.add("card-title");
    productName.textContent = ad.productName;
    detailsBody.appendChild(productName);

    let productDescription = document.createElement("p");
    productDescription.classList.add("card-text");
    productDescription.textContent = ad.productDescription;
    detailsBody.appendChild(productDescription);

    let listGroup = document.createElement("ul");
    listGroup.classList.add("list-group", "list-group-flush");
    detailsBody.appendChild(listGroup);

    let priceListItem = document.createElement("li");
    priceListItem.classList.add("list-group-item");
    priceListItem.innerHTML = '<strong>Price:</strong> €' + formatPricesInDecimal(ad.productPrice);
    listGroup.appendChild(priceListItem);

    let statusListItem = document.createElement("li");
    statusListItem.classList.add("list-group-item");
    statusListItem.innerHTML = '<strong>Status:</strong> ' + ad.productStatus;
    listGroup.appendChild(statusListItem);

    let postedDateListItem = document.createElement("li");
    postedDateListItem.classList.add("list-group-item");
    postedDateListItem.innerHTML = '<strong>Posted at: </strong>' + ad.postedDate;
    listGroup.appendChild(postedDateListItem);

    let buttonContainer = document.createElement("div");
    buttonContainer.classList.add("d-flex", "justify-content-end", "mb-2");
    detailsCol.appendChild(buttonContainer);

    return [card, buttonContainer];
}
function loadAdsOfLoggedUser() {

    const inputLoggedUserId = escapeHtml(document.getElementById("hiddenLoggedUserId").value);
    console.log('Logged User ID:', inputLoggedUserId);
    let data = { loggedUserId: inputLoggedUserId }
    fetch('http://localhost/api/adsbyloggeduser', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json'
        }
    }).then(response => response.json())
        .then(ads => {
            console.log('Ads:', ads);
            clearScreen();
            ads.forEach(function (ad) {
                if (ad.status === "Available") {
                    displayAvailableAds(ad);
                } else {
                    displayOtherStatusAds(ad);
                }
            })
        })
        .catch(err => console.error(err));
}

function validateForm(productName, price, description, image) {
    if (!productName) {
        alert('Please enter a product name');
        return false;
    }
    if (!price) {
        alert('Please enter a price');
        return false;
    }
    if (!description) {
        alert('Please enter a product description');
        return false;
    }
    if (!image) {
        alert('Please select an image');
        return false;
    }
    else if (!checkUploadedFile(image)) {
        return false;
    }
    return true;
}
function checkUploadedFile(image) {
    var fileType = image.type;
    var validImageTypes = ["image/jpg", "image/jpeg", "image/png"];

    if (validImageTypes.indexOf(fileType) < 0) {
        alert("Invalid file type. Please select an image file (jpg, jpeg, png)");
        return false;
    }
    return true;
}
function allowDrop(event) {
    event.preventDefault();
}

function dropFile(event) {
    const file = event.dataTransfer.files[0];
    document.getElementById("image").files[0] = file;
}

function displayPurchasedAd(ad) {

    console.log('Displaying purchased ad:', ad);
    const myPurchaseContainer = document.getElementById("myPurchaseContainer");
    let requireElements = createHorizontalPurchaseCard(ad);
    let card = requireElements[0];
    let buttonContainer = requireElements[1];

    myPurchaseContainer.appendChild(card);
}
function createHorizontalPurchaseCard(ad) {

    console.log('Displaying ad:', ad);

    let card = document.createElement("div");
    card.classList.add("card", "mb-3");
    card.style.maxWidth = "900px";
    card.style.position = "relative";

    let row = document.createElement("div");
    row.classList.add("row", "g-0");
    card.appendChild(row);

    let imageCol = document.createElement("div");
    imageCol.classList.add("col-md-4", "col-xl-4");
    row.appendChild(imageCol);

    let image = document.createElement("img");
    image.src = ad.productIamageURI;
    image.classList.add("img-fluid", "rounded-start");
    imageCol.appendChild(image);

    let detailsCol = document.createElement("div");
    detailsCol.classList.add("col-md-8", "col-xl-8", "d-flex", "flex-column", "justify-content-around", "align-items-center", "h-100");
    row.appendChild(detailsCol);

    let detailsBody = document.createElement("div");
    detailsBody.classList.add("card-body");
    detailsCol.appendChild(detailsBody);

    let productName = document.createElement("h5");
    productName.classList.add("card-title");
    productName.textContent = ad.productName;
    detailsBody.appendChild(productName);

    let productDescription = document.createElement("p");
    productDescription.classList.add("card-text");
    productDescription.textContent = ad.productDescription;
    detailsBody.appendChild(productDescription);

    let listGroup = document.createElement("ul");
    listGroup.classList.add("list-group", "list-group-flush");
    detailsBody.appendChild(listGroup);

    let priceListItem = document.createElement("li");
    priceListItem.classList.add("list-group-item");
    priceListItem.innerHTML = '<strong>Price:</strong> €' + formatPricesInDecimal(ad.productPrice);
    listGroup.appendChild(priceListItem);

    let statusListItem = document.createElement("li");
    statusListItem.classList.add("list-group-item");
    statusListItem.innerHTML = '<strong>Status:</strong> ' + ad.productStatus;
    listGroup.appendChild(statusListItem);

    let postedDateListItem = document.createElement("li");
    postedDateListItem.classList.add("list-group-item");
    postedDateListItem.innerHTML = '<strong>Posted at: </strong>' + ad.postedDate;
    listGroup.appendChild(postedDateListItem);

    return [card];
}
function displayAvailableAds(ad) {
    const myAdsContainer = document.getElementById("myAdsContainer");
    let requireElements = createHorizontalAdCard(ad);
    let card = requireElements[0];
    let buttonContainer = requireElements[1];

    let btnMarkAsSold = document.createElement('button');
    btnMarkAsSold.className = "btn btn-primary mx-2";
    btnMarkAsSold.innerHTML = "Mark As Sold";
    btnMarkAsSold.addEventListener('click', function () {
        btnMarkAsSoldClicked(ad.id);
    });
    buttonContainer.appendChild(btnMarkAsSold);

    const editButton = document.createElement('button');
    editButton.classList.add('btn', 'btn-secondary', 'mx-2');
    editButton.setAttribute('data-bs-toggle', 'modal');
    editButton.setAttribute('data-bs-target', '#editModal');
    editButton.addEventListener('click', () => {
        editAdButtonClicked(
            ad.id, ad.productImageURI, ad.productName, ad.productDescription, ad.productPrice,
        );
    });
    const icon = document.createElement('i');
    icon.classList.add('fa-solid', 'fa-file-pen');
    editButton.appendChild(icon);
    editButton.appendChild(document.createTextNode(' Edit'));

    buttonContainer.appendChild(editButton);

    let btnDeleteAd = document.createElement('button');
    btnDeleteAd.className = "btn btn-danger mx-2";
    btnDeleteAd.innerHTML = '<i class="fa-solid fa-trash"></i> Delete';
    btnDeleteAd.addEventListener('click', function () {
        btnDeleteAdClicked(ad.id, ad.productImageURI);
    });
    buttonContainer.appendChild(btnDeleteAd);
    myAdsContainer.appendChild(card);

}
function displayOtherStatusAds(ad) {
    const myAdsContainer = document.getElementById("myAdsContainer");
    let requireElements = createHorizontalAdCard(ad);
    let card = requireElements[0];
    let buttonContainer = requireElements[1];

    const markAsSoldButton = document.createElement("button");
    markAsSoldButton.classList.add("btn", "btn-primary", "mx-2");
    markAsSoldButton.disabled = true;
    markAsSoldButton.textContent = "Mark As Sold";
    buttonContainer.appendChild(markAsSoldButton);

    const editButton = document.createElement("button");
    editButton.classList.add("btn", "btn-secondary", "mx-2");
    editButton.disabled = true;
    editButton.innerHTML = '<i class="fa-solid fa-file-pen"></i> Edit';
    buttonContainer.appendChild(editButton);

    const deleteButton = document.createElement("button");
    deleteButton.classList.add("btn", "btn-danger", "mx-2");
    deleteButton.disabled = true;
    deleteButton.innerHTML = '<i class="fa-solid fa-trash"></i> Delete';
    buttonContainer.appendChild(deleteButton);

    const overlay = document.createElement("div");
    overlay.classList.add("overlay");
    overlay.style.position = "absolute";
    overlay.style.top = 0;
    overlay.style.left = 0;
    overlay.style.right = 0;
    overlay.style.bottom = 0;
    overlay.style.backgroundColor = "rgba(0,0,0,0.5)";
    overlay.style.display = "flex";
    overlay.style.alignItems = "center";
    overlay.style.justifyContent = "center";
    card.appendChild(overlay);

    let status = document.createElement("h2");
    status.style.color = "white";
    status.textContent = ad.status;
    overlay.appendChild(status);
    myAdsContainer.appendChild(card);
}
function btnMarkAsSoldClicked(adId) {
    event.preventDefault();
    sendUpdateRequestToAPi("ChangeStatusOfAd", adId, "");
}

function btnDeleteAdClicked(adId, image) {
    event.preventDefault();
    sendUpdateRequestToAPi("DeleteAd", adId, image,);

}

function sendUpdateRequestToAPi(typeOfOperation, adID, image) {
    let data = { OperationType: typeOfOperation, adID: adID, imageURI: image };

    fetch('http://localhost/api/updateAd', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json'
        }
    }).then(response => response.json())
        .then(response => {
            if (response.success) {
                loadAdsOfLoggedUser();
            } else {
                alert(response.message);
            }
        }).catch(err => console.error(err));
}

function clearScreen() {
    document.getElementById("myAdsContainer").innerHTML = "";
}
function editAdButtonClicked(adID, adImage, adProductName, adDescription, adPrice) {
    clearEveryInputInEditModel();
    setValuesForEditModel(adID, adImage, adProductName, adDescription.replace(/\\/g, ""), adPrice);
}

function clearEveryInputInEditModel() {
    document.getElementById("hiddenAdIdEditAdModal").value = "";
    document.getElementById("AdEditProductName").value = "";
    document.getElementById("AdEditPrice").value = "";
    document.getElementById("AdEditDescription").value = "";
    document.getElementById("AdEditImageURI").src = "";
    document.getElementById("AdEditImageInput").value = ""; // resetting the previous value in file select
}

function setValuesForEditModel(adId, adImage, adProductName, adDescription, adPrice) {
    document.getElementById("hiddenAdIdEditAdModal").value = (adId);
    document.getElementById("AdEditProductName").value = adProductName;
    document.getElementById("AdEditPrice").value = adPrice;
    document.getElementById("AdEditDescription").value = adDescription;
    document.getElementById("AdEditImageURI").src = adImage;
}

function previewImage(input) {
    if (!checkUploadedFile(input.files[0])) {
        return;
    }
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('AdEditImageURI').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}
async function editAdModalSaveChangeButtonClicked() {
    let adId = escapeHtml(document.getElementById("hiddenAdIdEditAdModal").value);
    let adProductName = escapeHtml(document.getElementById("AdEditProductName").value);
    let adProductPrice = escapeHtml(document.getElementById("AdEditPrice").value);
    let adDescription = escapeHtml(document.getElementById("AdEditDescription").value);
    let inputImageElement = document.getElementById("AdEditImageInput");
    let inputImage = inputImageElement.files[0];

    if (!inputImage) {
        inputImage = await getImageFileUsingPath();
    }
    if (!validateForm(adProductName, adProductPrice, adDescription, inputImage)) {
        return;
    }
    try {
        const data = {
            productName: adProductName,
            price: adProductPrice,
            productDescription: adDescription,
            adId: adId,
        };

        let formData = new FormData();
        formData.append("inputImage", inputImage);
        formData.append("editedAdDetails", JSON.stringify(data));

        await sendEditRequestToAPI(formData);
        document.getElementById("buttonCloseEditModal").click();
        loadAdsOfLoggedUser();
    } catch (error) {
        alert(error.message);
    }
}

function sendEditRequestToAPI(formData) {
    fetch('http://localhost/api/editAd', {
        method: 'POST',
        body: formData,
    })
        .then(function (response) {
            return response.json();
        })
        .then(function (responseData) {
            if (responseData.success) {
                document.getElementById("buttonCloseEditModal").click();
                loadAdsOfLoggedUser();
            } else {
                alert(responseData.message);
            }
        }).catch(err => console.error(err));
}

function getImageFileUsingPath() {
    let imgElement = document.getElementById('AdEditImageURI');
    let imgSrc = imgElement.src;
    return fetch(imgSrc)
        .then(response => response.blob())
        .then(blob => {
            let fileName = imgSrc.substring(imgSrc.lastIndexOf('/') + 1);
            let fileType = blob.type;
            let file = new File([blob], fileName, { type: fileType });
            return file;
        }).catch(err => console.error(err));

}
function onInputValueChangeForSearch(input) {
    let productName = escapeHtml(input.value);

    if (productName.trim() === "") {
        document.getElementById("searchResultsContainer").innerHTML = "";
        return;
    }

    fetch('http://localhost/api/searchproducts?name=' + productName)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(data => {
            console.log('Response Data:', data);
            return JSON.parse(data);
        })
        .then(ads => {
            document.getElementById("searchResultsContainer").innerHTML = "";
            if (ads.length > 0) {
                ads.forEach(function (ad) {
                    showSearchResult(ad);
                });
            } else {
                resultNotFoundForSearchMessage(productName);
            }
        })
        .catch(error => {
            console.error('Error during fetch:', error.message);
            console.error('Error status:', error.status);
        });



}

function resultNotFoundForSearchMessage(inputValue) {
    let errorMessage = document.createElement("h2");
    errorMessage.innerHTML = "Sorry, no search result found for " + '"' + inputValue + '"' + " 🙁";

    let containerId = "containerRowContainerAvailableAds";

    let container = document.getElementById(containerId);

    if (container) {
        container.innerHTML = "";
        container.appendChild(errorMessage);
    }
}
function showSearchResult(ad) {
    let col = document.createElement("div");
    col.classList.add("col-md-4", "col-sm-12", "col-xl-3", "my-3");

    let card = document.createElement("div");
    card.classList.add("card", "h-100", "shadow");

    let img = document.createElement("img");
    img.src = ad.productImageURI;
    img.classList.add("img-fluid", "card-img-top");
    img.alt = ad.productName;
    img.style.width = "300px";
    img.style.height = "300px";

    let cardBody = document.createElement("div");
    cardBody.classList.add("card-body");

    let h3 = document.createElement("h3");
    h3.classList.add("card-title");
    h3.textContent = ad.productName;

    let p = document.createElement("p");
    p.classList.add("card-text");
    p.textContent = ad.productDescription;

    let button = document.createElement("button");
    button.classList.add("btn", "btn-primary", "position-relative");
    button.type = "submit";
    button.innerHTML = "<i class='fa-solid fa-cart-plus'></i> €" + formatPricesInDecimal(ad.productPrice);
    // add event listener to button
    button.addEventListener("click", function () {
        addToCartClicked(ad.id);
    });

    let cardFooter = document.createElement("div");
    cardFooter.classList.add("card-footer");

    let pFooter = document.createElement("p");
    pFooter.classList.add("card-text");

    let small = document.createElement("small");
    small.classList.add("text-muted");
    small.textContent = ad.postedDate + " posted by";

    let strong = document.createElement("strong");
    strong.textContent = ad.user && ad.user.firstName ? ad.user.firstName : "Unknown";

    pFooter.appendChild(small);
    small.appendChild(strong);
    cardBody.appendChild(h3);
    cardBody.appendChild(p);
    cardBody.appendChild(button);
    cardFooter.appendChild(pFooter);
    card.appendChild(img);
    card.appendChild(cardBody);
    card.appendChild(cardFooter);
    col.appendChild(card);

    document.getElementById("containerRowContainerAvailableAds").appendChild(col);
    let containerId = "containerRowContainerAvailableAds"; // Change this if needed
    let container = document.getElementById("containerRowContainerAvailableAds");

    if (container) {
        container.appendChild(col);
    }

}
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
function addToCartClicked(adID) {

    let form = document.createElement("form");
    form.method = "post";
    form.action = "/homepage/shoppingCart";

    let input = document.createElement("input");
    input.type = "hidden";
    input.name = "AdID";
    input.value = adID;

    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}

function formatPricesInDecimal(price) {
    let formattedPrice = new Intl.NumberFormat('en-US', {
        style: 'decimal',
        useGrouping: true,
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(price);
    return formattedPrice;
}