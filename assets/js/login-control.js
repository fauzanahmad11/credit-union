const username = document.querySelector("input[type='text']");
const password = document.querySelector("input[name='password']");
const btnLogin = document.querySelector("button[name='login']");
const errorBox = document.querySelector(".error-box");
const formError = document.querySelector(".form-error");

btnLogin.addEventListener('click', function () {

    const xhr = new XMLHttpRequest();

    xhr.onload = function () {
        let responseObjcet = null;
        try {
            responseObjcet = JSON.parse(xhr.responseText);
        } catch {
            console.log('Could not parse JSON !');
        }

        if (responseObjcet) {
            handleResponse(responseObjcet);
        }
    }

    const requestData = `username=${username.value}&password=${password.value}`;
    xhr.open("post", "../../assets/library/login_control.php");
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send(requestData);
});

function handleResponse(responseObjcet) {
    if (responseObjcet.status) {
        console.log(responseObjcet.status);
        console.log(responseObjcet.user);
        if ((responseObjcet.user == "petugas")) {
            document.location.href = '../../index.php';
        }
        if ((responseObjcet.user == "anggota")) {
            document.location.href = '../../client/index.php';
        }
        errorBox.style.maxHeight = "0";
    } else {
        while (formError.firstChild) {
            formError.removeChild(formError.firstChild);
        }

        responseObjcet.message.forEach((errorMessage) => {
            const li = document.createElement('li');
            li.textContent = errorMessage;
            formError.appendChild(li);
        });

        errorBox.style.maxHeight = "100rem";
        console.log(responseObjcet.message);
    }
}