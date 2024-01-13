function showLoginFailed() {
    const currentDiv = document.getElementById("passwordDiv");

    // Check if the error message already exists
    if (!currentDiv.querySelector('.alert-danger')) {
        var newDiv = document.createElement("div");
        newDiv.className = "alert-danger pb-3";
        newDiv.style.color = "red";
        newDiv.innerHTML = "Please check your login credentials and try again!";
        currentDiv.appendChild(newDiv);
    }
}
