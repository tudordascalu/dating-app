// triggers
$('#navSignup').on('click', function () {
    $(this).addClass('selected');
    $('#navUsers').removeClass('selected');
    $('.users-page').fadeOut();
    $('.signup-page').fadeIn();
})

$('#navUsers').on('click', function () {
    $(this).addClass('selected');
    $('#navSignup').removeClass('selected');
    loadNextPage();
    $('.signup-page').fadeOut();
    $('.users-page').fadeIn();
})
$("#imageField").change(function () {
    const imageUrl = $(this).val();
    $(".profile-image").attr("src", imageUrl);
});

$('.btnBack').on('click', function () {
    $('.users-page').hide();
    // $('.btnBack').hi
    $('.signup-page').css({ 'display': 'flex' });
})
// Submit form
$('.signup-form').submit(() => {
    event.preventDefault();

    // get fields 
    const firstName = $('#nameField').val();
    const lastName = $('#lastNameField').val();
    const age = $('#ageField').val();
    const imageField = $('#imageField').val();
    let proceed = true;

    // test string
    var regEx = new RegExp("^[a-zA-Z]+$");
    if (!regEx.test(firstName)) {
        addError('#nameField');
        proceed = false;
    }

    if (!regEx.test(lastName)) {
        addError('#lastNameField');
        proceed = false;
    }

    if (proceed) {
        let jUser = {
            'firstName': firstName,
            'lastName': lastName,
            'age': age,
            'imageUrl': imageField
        }
        let users = null;
        if (!localStorage.users) {
            users = [
                jUser
            ];
        } else {
            users = JSON.parse(localStorage.users);
            users.push(jUser);
        }
        localStorage.users = JSON.stringify(users);
        loadNextPage();
    }
})

// methods

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

// backup image
function handleError(elem) {
    $(elem).attr('src', './assets/images/user-icon.png');
    addError('#imageField');
}

// append error class
function addError(elem) {
    $(elem).addClass('error');
    setTimeout(() => {
        $(elem).removeClass('error');
    }, 2500);
}
