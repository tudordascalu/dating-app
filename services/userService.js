
function apiGetUsers(sUserId) {
    const sUrl = "/api/server.php?reqType=getUsers&id=" + sUserId;
    return new Promise((resolve, reject) => {
      axios
        .get(sUrl)
        .then(response => {
          resolve(response.data);
        })
        .catch(error => {
          clearUserProfile();
          reject({ "message": "user is not logged in" });
        });
    });
  }