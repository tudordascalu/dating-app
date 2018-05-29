
function apiGetUsers(sUserId) {
    const sUrl = "/api/server.php?reqType=getUsers&id=" + sUserId;
    return new Promise((resolve, reject) => {
      axios
        .get(sUrl)
        .then(response => {
          resolve(response.data);
        })
        .catch(error => {
          reject({ "message": "user is not logged in" });
        });
    });
  }

  function apiGetMatches(sUserId) {
    const sUrl = "/api/server.php?reqType=getMatches&id=" + sUserId;
    return new Promise((resolve, reject) => {
      axios
        .get(sUrl)
        .then(response => {
          resolve(response.data);
        })
        .catch(error => {
          reject({ "message": "user is not logged in" });
        });
    });
  }

  function apiCheckNewMatch(sUserId) {
    const sUrl = "/api/server.php?reqType=newMatch&id=" + sUserId;
    return new Promise((resolve, reject) => {
      axios
        .get(sUrl)
        .then(response => {
          resolve(response.data);
        })
        .catch(error => {
          reject({ "message": "user is not logged in" });
        });
    });
  }

  function apiGetUser(sUserId, iInterest) {
    const sUrl = "/api/server.php?reqType=getUser&id=" + sUserId;
    let formData = new FormData();
    formData.append('interest', iInterest);
    return new Promise((resolve, reject) => {
      axios
        .post(sUrl, formData)
        .then(response => {
          resolve(response.data);
        })
        .catch(error => {
          reject({ "message": "user is not logged in" });
        });
    });
  }

  function apiSaveLocation(sUserId, sLat, sLong) {
    const sUrl = "/api/server.php?reqType=saveLocation&id=" + sUserId;
    let formData = new FormData();
    formData.append('longitude', sLong);
    formData.append('latitude', sLat);
    return new Promise((resolve, reject) => {
      axios
        .post(sUrl, formData)
        .then(response => {
          resolve(response.data);
        })
        .catch(error => {
          reject({ "message": "user is not logged in" });
        });
    });
  }


  function apiLike(sUserId, sEmail, sLike) {
    const sUrl = "/api/server.php?reqType=like&id=" + sUserId;
    const jData = {
      "email": sEmail,
      "like": sLike
    }
    let formData = new FormData();
    formData.append('email', sEmail);
    formData.append('like', sLike);
    return new Promise((resolve, reject) => {
      axios
        .post(sUrl, formData)
        .then(response => {
          resolve(response.data);
        })
        .catch(error => {
          reject({ "message": "user is not logged in" });
        });
    });
  }