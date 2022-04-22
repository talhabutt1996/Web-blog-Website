/*jslint es6 */
/*jslint browser: true */
/*jslint devel: true */
/*global window, displayInfoMessage, regexTester, processJsonResponse */

let addAdminForm = document.querySelector('.add-admin-form');
let fullNameField = document.getElementById('name-field');
let usernameField = document.getElementById('username-field');
let passwordField = document.getElementById('password-field');
let confirmPassField = document.getElementById('cpassword-field');

// check if any of the textfield is left empty
function anyEmptyField() {
    'use strict';
    if (fullNameField.value === '' || usernameField.value === ''
            || passwordField.value === '' || confirmPassField.value === '') {
        displayInfoMessage('Please fill out the empty fields', 'error');
        return true;
    }
    return false;
}

function highLightEmptyFields() {
    'use strict';
    if (fullNameField.value === '') {
        fullNameField.style.border = '1px solid #dc3545';
    } else {
        fullNameField.style.border = '1px solid #ced4da';
    }

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

    if (confirmPassField.value === '') {
        confirmPassField.style.border = '1px solid #dc3545';
    } else {
        confirmPassField.style.border = '1px solid #ced4da';
    }
}

// highlight particular textfield
function highLightTextField(textfield) {
    'use strict';
    textfield.style.border = '1px solid #dc3545';

    if (textfield === fullNameField) {
        usernameField.style.border = '1px solid #ced4da';
        passwordField.style.border = '1px solid #ced4da';
        confirmPassField.style.border = '1px solid #ced4da';
    } else if (textfield === usernameField) {
        fullNameField.style.border = '1px solid #ced4da';
        passwordField.style.border = '1px solid #ced4da';
        confirmPassField.style.border = '1px solid #ced4da';
    } else if (textfield === passwordField) {
        fullNameField.style.border = '1px solid #ced4da';
        usernameField.style.border = '1px solid #ced4da';
        confirmPassField.style.border = '1px solid #ced4da';
    }
}

// submit form to server using ajax
function ajaxFormSubmit() {
    'use strict';
    let ajaxRequest = new XMLHttpRequest();
    let url = 'add admin form.php';

    ajaxRequest.onreadystatechange = function () {
        if (ajaxRequest.readyState === 4 && ajaxRequest.status === 200) {
            processJsonResponse(ajaxRequest.responseText, null);

            // refresh page after 4 seconds to reload information from database
            setTimeout(function () {
                window.location.reload();
            }, 4000);
        }
    };

    ajaxRequest.open('POST', url, true);
    //ajaxRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    //ajaxRequest.setRequestHeader('HTTTP_X-Requested-With', 'XMLHttpRequest');
    ajaxRequest.send(new FormData(addAdminForm));
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

    // check if full name is in right format
    if (!(regexTester(/^[^\s][A-Za-z.\s]+[^\s]$/g, fullNameField.value))) {
        displayInfoMessage('Full name can only consist of Alphabets or . sign', 'error');
        highLightTextField(fullNameField);
        return;
    }

    // check if full name is atleast 6 characters long
    if (fullNameField.value.length < 6) {
        displayInfoMessage('Full name should contain atleast 6 characters', 'error');
        highLightTextField(fullNameField);
        return;
    }

    // check if username is in right format
    if (!(regexTester(/^[^\s][A-Za-z0-9_]+[^\s]$/g, usernameField.value))) {
        displayInfoMessage('Username can only consist of Alphabets, Numbers or underscore', 'error');
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
        displayInfoMessage('Password can only consist of Alphabets, Numbers or underscore', 'error');
        highLightTextField(passwordField);
        return;
    }

    // check if password is atleast 6 characters long
    if (passwordField.value.length < 6) {
        displayInfoMessage('Password should contain atleast 6 characters', 'error');
        highLightTextField(passwordField);
        return;
    }

    // check if both password fields contain same password
    if (passwordField.value !== confirmPassField.value) {
        displayInfoMessage('Password in both password fields does not match', 'error');
        highLightTextField(passwordField);
        highLightTextField(confirmPassField);
        return;
    }

    // submit form information to server via ajax
    ajaxFormSubmit();
}

// add submit event listener on form
addAdminForm.addEventListener('submit', validateForm);