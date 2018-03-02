$(".signup-form").on("submit", function (event) {
  event.preventDefault();
  var formData = new FormData($(this)[0]);
  apiPostForm(formData)
  .then(data => {
    console.log(data);
  })
    .catch(error => {
      console.log(error);
    });;
});

(".login-form").on("submit", function (event) {
  event.preventDefault();
  var formData = new FormData($(this)[0]);
  apiPostForm(formData)
  .then(data => {
    console.log(data);
    localStorage.setItem('USER_DATA', JSON.stringify(data))
  })
    .catch(error => {
      console.log(error);
    });;
});

function apiPostForm(formData) {
  return new Promise((resolve, reject) => {
    axios
      .post("/api/server.php?reqType=signup", formData)
      .then(response => {
        resolve(JSON.stringify(response.data));
      })
      .catch(error => {
        clearUserProfile();
        reject({ "message": "user is not logged in" });
      });
  });
}