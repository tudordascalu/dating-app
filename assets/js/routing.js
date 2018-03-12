$(document).ready(function () {
    // $('.pages').hide();
    showPage('tinder-page');
})

function showPage(page) {
    console.log(page);
    if (page === 'login-page' || page === 'signup-page') {
        $('.navbar-container').hide();
    } else {
        $('.navbar-container').show();
    }

    if (!routeGuard(page)) {
        showPage('login-page');
        return;
    }

    if (page == 'users-page') {
        const sId = verifyAuth();
        apiGetUsers(sId).then(data => {
            console.log(data);
            if (!data.data) {
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

    if (page == 'matches-page') {
        const sId = verifyAuth();
        apiGetMatches(sId).then(data => {
            console.log(data);
            if (data.status === 'forbidden') {
                showPage('login-page');
            } else if (data.status == 'error') {
                $('.card').hide();
                $('.error-message').show();
                $('.pages').hide();
                $('.matches-page').show();
            } else {
                const aMatches = data.data;
                appendBoxes('flex-container', aMatches)
            }
        }).catch(error => {
            console.log(error);
        })
        return;
    }

    if (page == 'tinder-page') {
        const sId = verifyAuth();
        apiGetUser(sId).then(data => {
            console.log(data, 'getUserData');
            console.log(data.data, 'data.data');
            if (data.status === 'forbidden') {
                showPage('login-page');
            } else if (data.status == 'error') {
                $('.card').hide();
                $('.error-message').show();
                $('.pages').hide();
                $('.tinder-page').show();
                return;
            }
            else {
                $('.card').show();
                $('.error-message').hide();
                const jUser = data.data;
                localStorage['TINDER_USER_DATA'] = JSON.stringify(jUser);
                $('.tinder-page .card-title').text(jUser.last_name + ', ' + jUser.age);
                $('.tinder-page .card-image img').attr('src', '/api/' + jUser.imageUrl);
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
    $('.' + page).show();
}
// check if user can see page
function routeGuard(page) {
    if (page == 'login-page' || page == 'signup-page') return true;

    if (verifyAuth()) return true;

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
