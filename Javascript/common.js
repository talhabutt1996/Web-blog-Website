/*global window */
/*jslint es6 */
/*jslint browser: true */
/*jslint devel: true */

// displays specified info message and also displays
// red error background or green success background based on the
// value of second argument passed in this function
let displayInfoMessage = function (message, messageType) {
    'use strict';
    let messageBlock = document.querySelector('#info-message-block');
    messageBlock.style.display = 'block';
    messageBlock.firstElementChild.textContent = message;

    if (messageType === 'error') {
        messageBlock.className = 'error-msg-block';
    } else {
        messageBlock.className = 'success-msg-block';
    }

    setTimeout(function () {
        messageBlock.style.maxHeight = '150px';
    }, 10);
};

// validate each field's value against its respective regex
let regexTester = function (regex, fieldValue) {
    'use strict';
    let regexTest = regex.test(fieldValue);
    return (regexTest === true);
};

// process json response from the server
// if second parameter is null, just display the success message
// otherwise move to the location set in moveToLocation argument
let processJsonResponse = function (responseData, moveToLocation) {
    'use strict';
    let jsonData = JSON.parse(responseData);
    let responseType = jsonData.messageType;
    let responseMessage = jsonData.message;

    if (responseType === 'success' && moveToLocation !== null) {
        window.location = moveToLocation;
        return;
    }

    displayInfoMessage(responseMessage, responseType);
};

// hide info message block after 5 seconds of adding new post
let hideInfoMessageBlock = function () {
    'use strict';
    let messageBlock = document.querySelector('#info-message-block');
    messageBlock.style.maxHeight = '0px';

    // set display to none after 0.6 seconds to completely hide it
    setTimeout(function () {
        messageBlock.style.display = 'none';
    }, 600);
};