function showLoginFailed() {
    const form = document.querySelector("form");
    const currentDiv = document.getElementById("passwordDiv");
    var newDiv = document.createElement("div");
    newDiv.className = "alert-danger pb-3";
    newDiv.style.color = "red";
    newDiv.innerHTML = "Please check your login credentials and try again!";
    currentDiv.appendChild(newDiv);
}
