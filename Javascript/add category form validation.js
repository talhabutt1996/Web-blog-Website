/*jslint es6 */
/*jslint browser: true */
/*jslint devel: true */
/*global window, displayInfoMessage, regexTester, processJsonResponse */
let categoryForm = document.querySelector('.add-category-form');
let addCategoryField = document.getElementById('category-field');

// check if any of the textfield is left empty
function anyEmptyField() {
    'use strict';
    if (addCategoryField.value === '') {
        displayInfoMessage('Please fill in the empty field', 'error');
        return true;
    }
    return false;
}

function highLightEmptyFields() {
    'use strict';
    if (addCategoryField.value === '') {
        addCategoryField.style.border = '1px solid #dc3545';
    } else {
        addCategoryField.style.border = '1px solid #ced4da';
    }
}

// highlight particular textfield
function highLightTextField(textfield) {
    'use strict';
    textfield.style.border = '1px solid #dc3545';
}

// submit login form to server using ajax
function ajaxFormSubmit() {
    'use strict';
    let ajaxRequest = new XMLHttpRequest();
    let url = 'add category.php';

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
    ajaxRequest.send(new FormData(categoryForm));
}

function validateForm(e) {
    'use strict';

    // prevent form submission
    e.preventDefault();

    if (anyEmptyField()) {
        displayInfoMessage('Please fill in the empty field', 'error');
        highLightEmptyFields();
        return;
    }

    // check if category name is in right format
    if (!(regexTester(/^[^\s][A-Za-z\s]+[^\s]$/g, addCategoryField.value))) {
        displayInfoMessage('Category name can only contain alphabets and white space in between words', 'error');
        highLightTextField(addCategoryField);
        return;
    }

    // check if username is atleast 3 characters long
    if (addCategoryField.value.length < 2) {
        displayInfoMessage('Category name should contain atleast 2 characters', 'error');
        highLightTextField(addCategoryField);
        return;
    }

    // submit form information to server via ajax
    ajaxFormSubmit();
}

// add submit event listener on login form
categoryForm.addEventListener('submit', validateForm);