$(".signup-form").on("submit", function (event) {
  event.preventDefault();
  var formData = new FormData($(this)[0]);
  sReqType = 'signup';
  apiPostForm(formData, sReqType)
  .then(data => {
    console.log(data);
    if(!data.data) {
      Materialize.toast("Make sure you filled up the form!", 3000);
      return;
    }
    showPage("login-page");
  })
    .catch(error => {
      console.log(error);
      Materialize.toast('Could not sign you up!', 3000);
    });;
});

$(".login-form").on("submit", function (event) {
  event.preventDefault();
  var formData = new FormData($(this)[0]);
  sReqType = 'login';
  apiPostForm(formData, sReqType)
  .then(data => {
    console.log(data);
    if(data.code == 403) {
      Materialize.toast("Please verify your account", 3000);
      return;
    }
    if(!data.data) {
      Materialize.toast("Username or password is incorrect", 3000);
      return;
    }
    localStorage.setItem('USER_DATA', JSON.stringify(data.data))
    // Materialize.toast("Successful login", 3000);
    showPage('tinder-page');
    $('.navbar-container').show();
  })
    .catch(error => {
      Materialize.toast("Username or password is incorrect", 3000);
    });;
});

function onLike(response) {
  const id = verifyAuth();
  console.log(id,'user id');
  const sLikeId = JSON.parse(localStorage['TINDER_USER_DATA']).id;
  console.log(sLikeId, 'tinderId');
  apiLike(id, sLikeId, response ).then(data => {
    console.log(data, 'tinder data')
    showPage('tinder-page');
  }).catch(error => {
    console.log(error);
  })
}

function onLogout() {
  localStorage.removeItem('USER_DATA');
  const id = verifyAuth();
  apiLogout(id).then(data => {
    console.log(data);
    showPage('login-page');
    $('.navbar-container').hide();
  }).catch(error => {
    console.log(error);
    showPage('login-page');
    $('.navbar-container').hide();
  })
  // if (!localStorage['USER_DATA']) return;

}