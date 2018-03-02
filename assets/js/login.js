$(".login-form").on("submit", function (event) {
    event.preventDefault();
    var formData = new FormData($(this)[0]);
    submitForm(formData)
    .then(data => {
      console.log(data);
    })
      .catch(error => {
        console.log(error);
      });;
  });
  
  function submitForm(formData) {
    return new Promise((resolve, reject) => {
      axios
        .post("/api/server.php?reqType=login", formData)
        .then(response => {
          console.log(response.data);
          localStorage.setItem('USER_DATA', JSON.stringify(response.data))
          resolve(JSON.stringify(response.data));
        })
        .catch(error => {
          clearUserProfile();
          reject({ "message": "user is not logged in" });
        });
    });
  }