/*jslint es6 */
/*jslint browser: true */
/*jslint devel: true */
/*global window, displayInfoMessage, regexTester, processJsonResponse,
         hideInfoMessageBlock */

let commentForm = document.querySelector('.comment-form');
let nameField = document.getElementById('name-field');
let emailField = document.getElementById('email-field');
let commentField = document.getElementById('comment-field');

// check if any of the textfield is left empty
function anyEmptyField() {
    'use strict';
    if (nameField.value === '' || emailField.value === '' || commentField.value === '') {
        displayInfoMessage('Please fill out the empty fields', 'error');
        return true;
    }
    return false;
}

function highLightEmptyFields() {
    'use strict';
    if (nameField.value === '') {
        nameField.style.border = '1px solid #dc3545';
    } else {
        nameField.style.border = '1px solid #ced4da';
    }

    if (emailField.value === '') {
        emailField.style.border = '1px solid #dc3545';
    } else {
        emailField.style.border = '1px solid #ced4da';
    }

    if (commentField.value === '') {
        commentField.style.border = '1px solid #dc3545';
    } else {
        commentField.style.border = '1px solid #ced4da';
    }
}

// highlight particular textfield
function highLightTextField(textfield) {
    'use strict';
    textfield.style.border = '1px solid #dc3545';

    if (textfield === nameField) {
        emailField.style.border = '1px solid #ced4da';
        commentField.style.border = '1px solid #ced4da';
    } else if (textfield === emailField) {
        nameField.style.border = '1px solid #ced4da';
        commentField.style.border = '1px solid #ced4da';
    } else if (textfield === commentField) {
        nameField.style.border = '1px solid #ced4da';
        emailField.style.border = '1px solid #ced4da';
    }
}

// clear input fields after new comment has been added
// also clear the red border highlight if any field has it due to error
function clearFields() {
    'use strict';
    nameField.value = '';
    emailField.value = '';
    commentField.value = '';

    nameField.style.border = '1px solid #ced4da';
    emailField.style.border = '1px solid #ced4da';
    commentField.style.border = '1px solid #ced4da';
}

// submit form to server using ajax
function ajaxFormSubmit() {
    'use strict';
    let ajaxRequest = new XMLHttpRequest();
    let url = 'save comment.php';

    ajaxRequest.onreadystatechange = function () {
        if (ajaxRequest.readyState === 4 && ajaxRequest.status === 200) {
            processJsonResponse(ajaxRequest.responseText, null);
            clearFields();

            // hide info message block after 5 seconds
            setTimeout(function () {
                hideInfoMessageBlock();
            }, 4000);
        }
    };

    ajaxRequest.open('POST', url, true);
    //ajaxRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    //ajaxRequest.setRequestHeader('HTTTP_X-Requested-With', 'XMLHttpRequest');
    ajaxRequest.send(new FormData(commentForm));
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

    // check if user's name is in right format
    if (!(regexTester(/^[^\s][A-Za-z\.\s]+[^\s]$/g, nameField.value))) {
        displayInfoMessage('Full name can only consist of Alphabets or . sign', 'error');
        highLightTextField(nameField);
        return;
    }

    // check if usern's name is atleast 6 characters long
    if (nameField.value.length < 6) {
        displayInfoMessage('Name should contain atleast 6 characters', 'error');
        highLightTextField(nameField);
        return;
    }

    // check if user's email is in right format
    if (!(regexTester(/^[^\s]\S+@\S+[^\s]$/g, emailField.value))) {
        displayInfoMessage('Invalid email address', 'error');
        highLightTextField(emailField);
        return;
    }

    // check if email is atleast 3 characters long
    if (emailField.value.length < 3) {
        displayInfoMessage('email should be atleast 3 characters long', 'error');
        highLightTextField(emailField);
        return;
    }

    // check if comment is in right format
    if (!(regexTester(/^[^\s][A-Za-z0-9\W]+[^\s]$/g, commentField.value))) {
        displayInfoMessage('Comment format not valid, cannot have white space or special characters at the start or at the end', 'error');
        highLightTextField(commentField);
        return;
    }

    // check if comment is atleast 10 characters long
    if (commentField.value.length < 10) {
        displayInfoMessage('Comment should be atleast 10 characters long', 'error');
        highLightTextField(commentField);
        return;
    }

    // submit form information to server via ajax
    ajaxFormSubmit();
}

// add submit event listener on form
commentForm.addEventListener('submit', validateForm);