/*jslint es6 */
/*jslint browser:true */
let sideBarBtn = document.querySelector('.sidebar-toggler');
let sidebar = document.querySelector('.sidebar-container');

sideBarBtn.addEventListener('click', function () {
    'use strict';
    sidebar.classList.toggle('collapse-sidebar');
});
