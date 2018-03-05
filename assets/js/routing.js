$(document).ready(function() {
    showPage('login-page');
    console.log('da');
})
function showPage(page) {
    console.log(page);
    if(!routeGuard(page)) {
        showPage('login-page');
        return;
    }

    if(page == 'users-page') {
        const sId = verifyAuth();
        apiGetUsers(sId).then(data => {
            console.log(data);
            if(!data.data) {
                showPage('login-page');
            } else {
                const aUsers = data.data; 
                appendBoxes('flex-container', aUsers)
            }
        }).catch(error => {
            console.log(error);
        })
        return;
    }

    if(page == 'tinder-page') {
        const sId = verifyAuth();
        apiGetUser(sId).then(data => {
            console.log(data.data, 'data.data');
            if(!data.data) {
                showPage('login-page');
            } else {
                const jUser = data.data; 
                $('.tinder-page .card-title').text(jUser.last_name+ ', '+jUser.age);
                $('.tinder-page .card-image img').attr('src', jUser.imageUrl);
                $('.pages').hide();
                $('.tinder-page').show();
                return;
                // appendBoxes('flex-container', aUsers)
            }
        }).catch(error => {
            console.log(error);
        })
        return;
    }

    $('.pages').hide();
    $('.'+page).show();
}
// check if user can see page
function routeGuard(page) {
    if(page == 'login-page' || page == 'signup-page') return true;

    if(verifyAuth()) return true;

    return false;
}



// add users to DOM
function appendBoxes(elem, users) {
    // delete users
    $('.' + elem).empty();
    console.log(users);
    // add new users
    for (let i = 0; i < users.length; i++) {
        jUser = users[i];
        box = ' <div class="card"> <div class="image"> <img onerror="handleError(this)" src="./api' + jUser.imageUrl + '"></div> <div class="text"> <h1>' + jUser.first_name + ' ' + jUser.last_name + ', ' + jUser.age + '</h1> </div></div>'
        $('.' + elem).append(box);
    }
    const emptyBox = ""
    $('.' + elem).append('<div style="width:350px;margin-right:20px;"></div>');
    $('.' + elem).append('<div style="width:350px;margin-right:20px;"></div>');
    $('.' + elem).append('<div style="width:350px;margin-right:20px;"></div>');
    
    $('.pages').hide();
    $('.users-page').show();
}

function handleError(elem) {
    $(elem).attr('src', './assets/images/user-icon.png');
    addError('#imageField');
}
