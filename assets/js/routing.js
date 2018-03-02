$(document).ready(function() {
    showPage('login-page');
    console.log('da');
})
function showPage(page) {
    $('.pages').hide();
    $('.'+page).show();
}