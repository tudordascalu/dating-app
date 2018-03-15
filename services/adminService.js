function apiAdminGetUsers(sUserId) {
    const sUrl = '/api/server.php?reqType=adminGetUsers';
    let formData = new FormData();
    formData.append('id', sUserId);
    return new Promise((resolve, reject) => {
        axios
          .post(sUrl, formData)
          .then(response => {
            localStorage.setItem('USERS', JSON.stringify(response.data.data));
            resolve(response.data);
          })
          .catch(error => {
            reject({ "message": "could not get users" });
          });
      });
}

function apiAdminSaveUsers(sUserId, ajUsers) {
  const sUrl = '/api/server.php?reqType=adminSaveUsers';
  let formData = new FormData();
  formData.append('id', sUserId);
  const sajUsers = JSON.stringify(ajUsers);
  formData.append('users', sajUsers);
  return new Promise((resolve, reject) => {
      axios
        .post(sUrl, formData)
        .then(response => {
          resolve(response.data);
        })
        .catch(error => {
          reject({ "message": error });
        });
    });
}