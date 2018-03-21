$(document).ready(function () {
    if (verifyAdmin()) {
        showPage('admin-page');
    } else {
        showPage('tinder-page');
    }

    // materialize init
    $('select').material_select();
    $('.modal').modal();
})

function showPage(page) {
    showNavbar(page);

    if (!routeGuard(page)) {
        showPage('login-page');
        return;
    }
    const sId = verifyAuth();
    switch (page) {
        case 'users-page':
            apiGetUsers(sId).then(data => {
                if (!data.data) {
                    showPage('login-page');
                } else {
                    const aUsers = data.data;
                    appendBoxes('flex-container', aUsers)
                }
            }).catch(error => {
            })
            break;

        case 'matches-page':
            apiGetMatches(sId).then(data => {
                if (data.status === 'forbidden') {
                    showPage('login-page');
                } else if (data.status == 'error') {
                    $('.card').hide();
                    $('.error-message').text('You have no matches, keep swiping');
                    $('.error-message').show();
                    $('.pages').hide();
                    $('.matches-page').fadeIn(500);
                } else {
                    const aMatches = data.data;
                    appendBoxes('flex-container', aMatches, true)
                }
            }).catch(error => {
            })
            break;

        case 'tinder-page':
            const iInterest = getUserInterest();
            apiGetUser(sId, iInterest).then(data => {
                if (data.status === 'forbidden') {
                    showPage('login-page');
                } else if (data.status == 405) {
                    $('.card').hide();
                    $('.error-message').text('Come back tomorrow or update to VIP for more swipes');
                    $('.error-message').show();
                    $('.pages').hide();
                    $('.tinder-page').fadeIn(500);
                } else if (data.status == 'error') {
                    $('.card').hide();
                    $('.error-message').text('No more users, stay put');
                    $('.error-message').show();
                    $('.pages').hide();
                    $('.tinder-page').fadeIn(500);
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
                    $('.tinder-page').fadeIn(500);
                }
            }).catch(error => {
            })
            break;

        case 'admin-page':
            apiAdminGetUsers(sId).then(data => {

                if (data.status != 200) {
                    showPage('login-page');
                }
                else {
                    let ajUsers = data.data;
                    initializeTable(ajUsers);
                    $('.pages').hide();
                    $('.' + page).fadeIn(500);
                }
            }).catch(error => {
                showPage('login-page');
            })
            break;

        case 'profile-page':
                initializeProfilePage(page);
            break;

        default:
            $('.pages').hide();
            $('.' + page).css({ 'opacity': 1 });
            $('.' + page).fadeIn(500);

            break;
    }
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
        box = ' <div class="card sticky-action"> <div class="image"> <img onerror="handleError(this)" src="./api' + jUser.imageUrl + '"></div> <div class="text activator"> <h1>' + jUser.first_name + ' ' + jUser.last_name + ', ' + jUser.age
            + '<i style="cursor:pointer" class="material-icons right">more_vert</i></h1></div>'
            + '<div class="card-reveal"> <span class="card-title grey-text text-darken-4">' + jUser.first_name + '<i class="material-icons right">close</i></span>'
            + '<p>' + jUser.description + '</p>';
        if (isMatchesPage) {
            box += '<p> Emal: ' + jUser.email + '</p>';
        }

        if (jUser.latitude && jUser.longitude) {
            box += '<button class="location-button btn modal-trigger" onclick="seeLocation(' + jUser.latitude + ',' + jUser.longitude + ')">See Location</button>';
        }

        box += '</div>';
        $('.' + elem).append(box);
    }

    const emptyBox = ""
    $('.' + elem).append('<div style="width:350px;margin-right:20px;"></div>');
    $('.' + elem).append('<div style="width:350px;margin-right:20px;"></div>');
    $('.' + elem).append('<div style="width:350px;margin-right:20px;"></div>');

    $('.pages').hide();
    $('.users-page').fadeIn(500);
}

function handleError(elem) {
    $(elem).attr('src', './assets/images/user-icon.png');
    addError('#imageField');
}

function showNavbar(page) {
    if (page === 'login-page' || page === 'signup-page') {
        $('.navbar-container').hide();
        $('.navbar-admin').hide();
    } else {
        if (page === 'admin-page') {
            $('.navbar-admin').fadeIn(500);
        } else {
            $('.navbar-container').fadeIn(500);
        }
    }
}

function initializeTable(ajUsers) {
    $('.table-body').empty();
    ajUsers.forEach(function (jUser) {
        let domElement = `
            <tr>
                <td>
                    ${jUser.first_name}
                </td>
                <td>
                    ${jUser.last_name}
                </td>
                <td>
                    <select onchange="onUpdateRole(this, '${jUser.id}')">
                        <option value="normal" ${jUser.role != 'vip' ? 'selected' : ''}>Regular</option>
                        <option value="vip" ${jUser.role == 'vip' ? 'selected' : ''}>VIP</option>
                    </select>
                </td>
                <td style="text-align: center">
                    <button onclick="onDeleteUser('${jUser.id}')" class="no-style">
                        <i style="cursor:pointer; color:lightcoral" class="material-icons">delete</i>
                    </butto>
                </td>
            </tr>
        `;
        $('.table-body').append(domElement);
    });
    $('select').material_select();
}

function initializeProfilePage(page) {
    $('.profile-container').empty();
    if(!verifyAuth()) return;    
    jUser = JSON.parse(localStorage.getItem('USER_DATA'));

    let table = `
    <div class="section">
        <h5>Name</h5>
        <p>${jUser.first_name} ${jUser.last_name}</p>
    </div>
    <div class="divider"></div>
    <div class="section">
        <h5>Email</h5>
        <p>${jUser.email}</p>
    </div>
    <div class="divider"></div>
    <div class="section">
        <h5>Description</h5>
        <p>${jUser.description}</p>
    </div>
    `;

    $('.profile-container').append(table);
    $('.pages').hide();
    $('.' + page).fadeIn(500);

}