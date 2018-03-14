
function apiGetUsers(sUserId) {
    const sUrl = "/api/server.php?reqType=getUsers&id=" + sUserId;
    return new Promise((resolve, reject) => {
      axios
        .get(sUrl)
        .then(response => {
          resolve(response.data);
        })
        .catch(error => {
          // clearUserProfile();
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
          // clearUserProfile();
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
          // clearUserProfile();
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
        .post(sUrl)
        .then(response => {
          resolve(response.data);
        })
        .catch(error => {
          // clearUserProfile();
          reject({ "message": "user is not logged in" });
        });
    });
  }


  function apiLike(sUserId, sLikeId, sLike) {
    const sUrl = "/api/server.php?reqType=like&id=" + sUserId;
    const jData = {
      "likeId": sLikeId,
      "like": sLike
    }
    let formData = new FormData();
    formData.append('likeId', sLikeId);
    formData.append('like', sLike);
    return new Promise((resolve, reject) => {
      axios
        .post(sUrl, formData)
        .then(response => {
          resolve(response.data);
          console.log(response.data, "response.data");
        })
        .catch(error => {
          // clearUserProfile();
          reject({ "message": "user is not logged in" });
        });
    });
  }