
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

  function apiLogout(sId) {
    const sUrl = "/api/server.php?reqType=logout&id=" + sId;
    return new Promise((resolve, reject) => {
      axios
        .get(sUrl)
        .then(response => {
          clearUserProfile();
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

  function verifyAdmin() {
    if(!localStorage['USER_DATA']) return false;
    const jData = JSON.parse(localStorage['USER_DATA']);
    if(jData.role == 'admin') return true;
    return false;
}

  function getUserInterest() {
    if(!localStorage['USER_DATA']) return -1;
    const jData = JSON.parse(localStorage['USER_DATA']);
    return jData.interest;
  }


function onLogout() {
    if (!localStorage['USER_DATA']) return;
    
    localStorage.removeItem('USER_DATA');
  }

function clearUserProfile() {
  localStorage.removeItem('USER_DATA');
}