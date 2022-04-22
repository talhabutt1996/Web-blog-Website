/*jslint es6 */
/*jslint browser: true */
/*jslint devel: true */
/*global window, displayInfoMessage, regexTester, processJsonResponse */
let loginForm = document.querySelector('.login-form');
let usernameField = document.getElementById('username-field');
let passwordField = document.getElementById('password-field');

// check if any of the textfield is left empty
function anyEmptyField() {
    'use strict';
    if (usernameField.value === '' || passwordField.value === '') {
        displayInfoMessage('Please fill out the empty fields', 'error');
        return true;
    }
    return false;
}

function highLightEmptyFields() {
    'use strict';
    if (usernameField.value === '') {
        usernameField.style.border = '1px solid #dc3545';
    } else {
        usernameField.style.border = '1px solid #ced4da';
    }

    if (passwordField.value === '') {
        passwordField.style.border = '1px solid #dc3545';
    } else {
        passwordField.style.border = '1px solid #ced4da';
    }
}

// highlight particular textfield
function highLightTextField(textfield) {
    'use strict';
    textfield.style.border = '1px solid #dc3545';

    if (textfield === usernameField) {
        passwordField.style.border = '1px solid #ced4da';
    } else if (textfield === passwordField) {
        usernameField.style.border = '1px solid #ced4da';
    }
}

// submit login form to server using ajax
function ajaxFormSubmit() {
    'use strict';
    let ajaxRequest = new XMLHttpRequest();
    let url = 'login form.php';

    ajaxRequest.onreadystatechange = function () {
        if (ajaxRequest.readyState === 4 && ajaxRequest.status === 200) {
            processJsonResponse(ajaxRequest.responseText, '../Admin Home/admin dashboard.php');
        }
    };

    ajaxRequest.open('POST', url, true);
    //ajaxRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    //ajaxRequest.setRequestHeader('HTTTP_X-Requested-With', 'XMLHttpRequest');
    ajaxRequest.send(new FormData(loginForm));
}

function validateForm(e) {
    'use strict';

    // prevent form submission
    e.preventDefault();

    if (anyEmptyField()) {
        displayInfoMessage('Please fill all the empty fields', 'error');
        highLightEmptyFields();
        return;
    }

    // check if username is in right format
    if (!(regexTester(/^[^\s][A-Za-z0-9_]+[^\s]$/g, usernameField.value))) {
        displayInfoMessage('Username format not valid', 'error');
        highLightTextField(usernameField);
        return;
    }

    // check if username is atleast 3 characters long
    if (usernameField.value.length < 3) {
        displayInfoMessage('Username should contain atleast 3 characters', 'error');
        highLightTextField(usernameField);
        return;
    }

    // check if password is in right format
    if (!(regexTester(/^[^\s][A-Za-z0-9_]+[^\s]$/g, passwordField.value))) {
        displayInfoMessage('Password format not valid', 'error');
        highLightTextField(passwordField);
        return;
    }

    // check if password is atleast 6 characters long
    if (passwordField.value.length < 6) {
        displayInfoMessage('Password should contain atleast 6 characters', 'error');
        highLightTextField(passwordField);
        return;
    }

    // submit form information to server via ajax
    ajaxFormSubmit();
}

// add submit event listener on login form
loginForm.addEventListener('submit', validateForm);