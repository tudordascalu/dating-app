$(".signup-form").on("submit", function (event) {
  event.preventDefault();
  var formData = new FormData($(this)[0]);
  sReqType = 'signup';
  apiPostForm(formData, sReqType)
  .then(data => {
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
    if(!data.data) {
      Materialize.toast("Username or password is incorrect", 3000);
      return;
    }
    localStorage.setItem('USER_DATA', JSON.stringify(data.data))
    Materialize.toast("Successful login", 3000);
  })
    .catch(error => {
      Materialize.toast("Username or password is incorrect", 3000);
    });;
});

function onLogout() {
  if (!localStorage['USER_DATA']) return;
  
  localStorage.removeItem('USER_DATA');
}