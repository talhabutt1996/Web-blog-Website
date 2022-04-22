/*jslint es6 */
/*jslint browser: true */
/*jslint devel: true */
/*global window, displayInfoMessage, regexTester, processJsonResponse,
         hideInfoMessageBlock */

let newPostForm = document.getElementById('new-post-form');
let postTitleField = document.getElementById('post-title-field');
let postCategoryField = document.getElementById('post-category-field');
let postBannerField = document.getElementById('post-banner-field');
let postContentField = document.getElementById('post-content-field');

// check if any of the textfield is left empty
function anyEmptyField() {
    'use strict';
    if (postTitleField.value === '' || postCategoryField.value === 'none selected'
            || postBannerField.value === '' || postContentField.value === '') {
        displayInfoMessage('Please fill out the empty fields', 'error');
        return true;
    }
    return false;
}

function highLightEmptyFields() {
    'use strict';
    if (postTitleField.value === '') {
        postTitleField.style.border = '1px solid #dc3545';
    } else {
        postTitleField.style.border = '1px solid #ced4da';
    }

    if (postCategoryField.value === 'none selected') {
        postCategoryField.style.border = '1px solid #dc3545';
    } else {
        postCategoryField.style.border = '1px solid #ced4da';
    }

    if (postBannerField.value === '') {
        postBannerField.style.border = '1px solid #dc3545';
    } else {
        postBannerField.style.border = '1px solid #ced4da';
    }

    if (postContentField.value === '') {
        postContentField.style.border = '1px solid #dc3545';
    } else {
        postContentField.style.border = '1px solid #ced4da';
    }
}

// highlight particular textfield
function highLightTextField(textfield) {
    'use strict';
    textfield.style.border = '1px solid #dc3545';

    if (textfield === postTitleField) {
        postCategoryField.style.border = '1px solid #ced4da';
        postBannerField.style.border = '1px solid #ced4da';
        postContentField.style.border = '1px solid #ced4da';
    } else if (textfield === postCategoryField) {
        postTitleField.style.border = '1px solid #ced4da';
        postBannerField.style.border = '1px solid #ced4da';
        postContentField.style.border = '1px solid #ced4da';
    } else if (textfield === postBannerField) {
        postCategoryField.style.border = '1px solid #ced4da';
        postTitleField.style.border = '1px solid #ced4da';
        postContentField.style.border = '1px solid #ced4da';
    } else if (textfield === postContentField) {
        postTitleField.style.border = '1px solid #ced4da';
        postCategoryField.style.border = '1px solid #ced4da';
        postBannerField.style.border = '1px solid #ced4da';
    }
}

// check if post banner field has only image file selected
function imageFileSelected() {
    'use strict';
    let fileName = postBannerField.value;
    let allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;

    if (!allowedExtensions.test(fileName)) {
        postBannerField.value = '';
        return false;
    }
    return true;
}

// clear input fields after new post has been added
function clearFields() {
    'use strict';
    postTitleField.value = '';
    postCategoryField.value = '';
    postBannerField.value = '';
    postContentField.value = '';
}

// submit form to server using ajax
function ajaxFormSubmit() {
    'use strict';
    let ajaxRequest = new XMLHttpRequest();
    let url = 'save post.php';

    ajaxRequest.onreadystatechange = function () {
        if (ajaxRequest.readyState === 4 && ajaxRequest.status === 200) {
            processJsonResponse(ajaxRequest.responseText, null);
            clearFields();

            // hide info message block after 5 seconds
            setTimeout(function () {
                hideInfoMessageBlock();
            }, 5000);
        }
    };

    ajaxRequest.open('POST', url, true);
    //ajaxRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    //ajaxRequest.setRequestHeader('HTTTP_X-Requested-With', 'XMLHttpRequest');
    ajaxRequest.send(new FormData(newPostForm));
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

    // check if post title is in right format
    if (!(regexTester(/^[^\s][A-Za-z0-9\s\-]+[^\s]$/g, postTitleField.value))) {
        displayInfoMessage('Post title format not valid', 'error');
        highLightTextField(postTitleField);
        return;
    }

    // check if post title is atleast 3 characters long
    if (postTitleField.value.length < 3) {
        displayInfoMessage('Post title should contain atleast 3 characters', 'error');
        highLightTextField(postTitleField);
        return;
    }

    // check if post banner field has image file selected
    if (!imageFileSelected()) {
        displayInfoMessage('Only image (jpeg/png) file allowed', 'error');
        highLightTextField(postBannerField);
        return;
    }

    // check if post content field has atleast 10 characters
    if (postContentField.value.length < 10) {
        displayInfoMessage('Post content should be atleast 10 characters long', 'error');
        highLightTextField(postContentField);
        return;
    }

    // submit form information to server via ajax
    ajaxFormSubmit();
}

// add submit event listener on form
newPostForm.addEventListener('submit', validateForm);