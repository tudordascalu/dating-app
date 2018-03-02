$(".signup-form").on("submit", function (event) {
  event.preventDefault();
  var formData = new FormData($(this)[0]);
  sReqType = 'signup';
  apiPostForm(formData, sReqType)
  .then(data => {
    console.log(data);
  })
    .catch(error => {
      console.log(error);
    });;
});

$(".login-form").on("submit", function (event) {
  event.preventDefault();
  var formData = new FormData($(this)[0]);
  sReqType = 'login';
  apiPostForm(formData, sReqType)
  .then(data => {
    localStorage.setItem('USER_DATA', JSON.stringify(data.data))
  })
    .catch(error => {
      console.log(error);
    });;
});

function onLogout() {
  if (!localStorage['USER_DATA']) return;
  
  localStorage.removeItem('USER_DATA');
}
function apiPostForm(formData, sReqType) {
  const sUrl = "/api/server.php?reqType=" + sReqType;
  return new Promise((resolve, reject) => {
    axios
      .post(sUrl, formData)
      .then(response => {
        resolve(response.data);
      })
      .catch(error => {
        clearUserProfile();
        reject({ "message": "user is not logged in" });
      });
  });
}