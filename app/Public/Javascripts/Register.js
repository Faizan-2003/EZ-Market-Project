function validate(input){
    let regex = /[^a-zA-Z\s]/;
    if(regex.test(input.value)) {
        input.classList.add("invalid");
        document.getElementById("btnRegister").disabled=true;
    } else {
        input.classList.remove("invalid");
        document.getElementById("btnRegister").disabled=false;
    }
}
function confirmRepeatedPassword(inputField){
    if(inputField.value.length>=8 | inputField.value.length>0){
        if(inputField.value!==document.getElementById("password").value)
        {
            document.getElementById("feedback-invalid").innerText="Your repeated password must same";
            document.getElementById("feedback-invalid").style.color="red";
            document.getElementById("btnRegister").disabled=true;
            inputField.classList.add("invalid");
        }else{
            inputField.classList.remove("invalid");
            document.getElementById("feedback-invalid").innerText="";
            document.getElementById("btnRegister").disabled=false;
        }
    }
    else{
        inputField.classList.remove("invalid");
        document.getElementById("feedback-invalid").innerText="";
        document.getElementById("btnRegister").disabled=false;
    }
}
function verifyPasswordLength(inputField){
    if(inputField.value.length<=8 && inputField.value.length>0) {
        document.getElementById("feedback-invalid-pass").innerText = "You must enter a 8-character length password";
        document.getElementById("feedback-invalid-pass").style.color = "red";
        inputField.classList.add("invalid");
        document.getElementById("btnRegister").disabled=true;
    }
    else{
        inputField.classList.remove("invalid");
        document.getElementById("feedback-invalid-pass").innerText="";
        document.getElementById("btnRegister").disabled=false;
    }
}
function displayModalForSignUp(title,message){

    let buttonName,href;
    if(title=="Ohoooo!")
    {
        buttonName ='Try again';
        href="/homepage/login/register";
    }
    else{
        buttonName ='Login';
        href="/homepage/login";
    }
    let modal = document.createElement("div");
    modal.classList.add("modal", "fade");
    modal.id = "myModal";
    modal.setAttribute("tabindex", "-1");
    modal.setAttribute("role", "dialog");
    modal.setAttribute("aria-labelledby", "myModalLabel");
    modal.setAttribute("aria-hidden", "true");
    document.body.appendChild(modal);

    let modalDialog = document.createElement("div");
    modalDialog.classList.add("modal-dialog");
    modalDialog.setAttribute("role", "document");
    modal.appendChild(modalDialog);

    let modalContent = document.createElement("div");
    modalContent.classList.add("modal-content");
    modalDialog.appendChild(modalContent);

    let modalHeader = document.createElement("div");
    modalHeader.classList.add("modal-header");
    modalContent.appendChild(modalHeader);

    let modalTitle = document.createElement("h5");
    modalTitle.classList.add("modal-title");
    modalTitle.innerText = title;
    modalTitle.id = "myModalLabel";
    modalHeader.appendChild(modalTitle);

    let modalCloseBtn = document.createElement("button");
    modalCloseBtn.type = "button";
    modalCloseBtn.classList.add("btn-close");
    modalCloseBtn.setAttribute("data-bs-dismiss", "modal");
    modalCloseBtn.setAttribute("aria-label", "Close");
    modalCloseBtn.addEventListener("click", function() {
        window.location.href = href;
    });
    modalHeader.appendChild(modalCloseBtn);

    let modalBody = document.createElement("div");
    modalBody.classList.add("modal-body");
    modalBody.innerText = message;
    modalContent.appendChild(modalBody);

    let modalFooter = document.createElement("div");
    modalFooter.classList.add("modal-footer");
    modalContent.appendChild(modalFooter);

    let modalCloseBtn2 = document.createElement("button");
    modalCloseBtn2.type = "button";
    modalCloseBtn2.classList.add("btn", "btn-primary");
    modalCloseBtn2.innerText = buttonName;
    modalCloseBtn2.setAttribute("data-bs-dismiss", "modal");
    modalFooter.appendChild(modalCloseBtn2);
    modalCloseBtn2.addEventListener("click", function() {
        window.location.href = href;
    });

    let modalBtn = document.createElement("button");
    modalBtn.type = "button";
    modalBtn.hidden=true;
    modalBtn.classList.add("btn", "btn-primary");
    modalBtn.innerText = "Open Modal";
    modalBtn.setAttribute("data-bs-toggle", "modal");
    modalBtn.setAttribute("data-bs-target", "#myModal");
    document.body.appendChild(modalBtn);
    modalBtn.click();
}