
// function loadNextPage() {
//     deleteChildren('flex-container');
//     appendBoxes('flex-container');
// }

// function deleteChildren(elem) {
//     $('.' + elem).empty();
// }

// // add users to DOM
// function appendBoxes(elem) {

//     let user = JSON.parse(localStorage.users);
//     for (let i = 0; i < user.length; i++) {
//         box = ' <div class="card"> <div class="image"> <img onerror="handleError(this)" src="' + user[i].imageUrl + '"></div> <div class="text"> <h1>' + user[i].firstName + ' ' + user[i].lastName + ', ' + user[i].age + '</h1> </div></div>'
//         $('.' + elem).append(box);
//     }
//     const emptyBox = ""
//     $('.' + elem).append('<div style="width:350px;margin-right:20px;"></div>');
//     $('.' + elem).append('<div style="width:350px;margin-right:20px;"></div>');
//     $('.' + elem).append('<div style="width:350px;margin-right:20px;"></div>');
//     $('.signup-page').fadeOut();
//     $('.users-page').fadeIn();

// }
getUsers();
function getUsers() {
    const sId = verifyAuth();
    if(!sId) {
        showPage('login-page');
        return;
    }
    
    apiGetUsers(sId).then(data => {
        console.log(data);
    }).catch(error => {
        console.log(error);
    })
}
