// request permission on page load
document.addEventListener('DOMContentLoaded', function () {
    if (Notification.permission !== "granted")
      Notification.requestPermission();
  });
  setInterval(() => {
    const sId = verifyAuth();
    if(!sId) return;
    
    apiCheckNewMatch(sId).then(data => {
        if(data.status == "success") {
            notifyMe();
        }
        console.log(data, 'data');
    }).catch(error => {
        console.log("no new match");
    })
  }, 500);
  function notifyMe() {
    if (Notification.permission !== "granted")
      Notification.requestPermission();
    else {
      var notification = new Notification('Notification title', {
        icon: 'http://cdn.sstatic.net/stackexchange/img/logos/so/so-icon.png',
        body: "You have a new match!",
      });
  
    //   notification.onclick = function () {
    //     showPage('matches-page'); 
    //   };
  
    }
  
  }