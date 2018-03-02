
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

  function verifyAuth() {
      if(!localStorage['USER_DATA']) return;
      const jData = JSON.parse(localStorage['USER_DATA']);
      return jData.id;
  }