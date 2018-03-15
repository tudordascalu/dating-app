$(document).ready(function () {
    showPage('tinder-page');
    $('.wrapper').css({'opacity': 1});
    
    // materialize init
    $('select').material_select();
    $('.modal').modal();
})

function showPage(page) {
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
                appendBoxes('flex-container', aMatches, true)
            }
        }).catch(error => {
            console.log(error);
        })
        return;
    }

    if (page == 'tinder-page') {
        const sId = verifyAuth();
        const iInterest = getUserInterest();
        apiGetUser(sId, iInterest).then(data => {
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
                $('.tinder-page .card-description').text(jUser.description);
                $('.tinder-page .card-image img').attr('src', '/api/' + jUser.imageUrl);
                $('.pages').hide();
                $('.tinder-page').show();
                return;
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
function appendBoxes(elem, users, isMatchesPage = false) {
    // delete users
    $('.' + elem).empty();
    // add new users
    for (let i = 0; i < users.length; i++) {
        jUser = users[i];
        console.log(jUser, 'jUser');
        box = ' <div class="card sticky-action"> <div class="image"> <img onerror="handleError(this)" src="./api' + jUser.imageUrl + '"></div> <div class="text activator"> <h1>' + jUser.first_name + ' ' + jUser.last_name + ', ' + jUser.age 
        + '<i style="cursor:pointer" class="material-icons right">more_vert</i></h1></div>'
        + '<div class="card-reveal"> <span class="card-title grey-text text-darken-4">' + jUser.first_name +'<i class="material-icons right">close</i></span>'
        + '<p>' + jUser.description + '</p>';
        if(isMatchesPage) {
            box += '<p> Emal: ' + jUser.email + '</p>';  
        } 

        if(jUser.latitude && jUser.longitude){
            box += '<button class="location-button btn modal-trigger" onclick="seeLocation('+ jUser.latitude + ',' + jUser.longitude + ')">See Location</button>';
        }

        box += '</div>';
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
