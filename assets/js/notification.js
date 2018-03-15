// request permission on page load
document.addEventListener('DOMContentLoaded', function () {
  $('select').material_select();
  if (Notification.permission !== "granted")
    Notification.requestPermission();
});
setInterval(() => {
  const sId = verifyAuth();
  if (!sId) return;

  apiCheckNewMatch(sId).then(data => {
    if (data.status == "success") {
      notifyMe();
    }
  }).catch(error => {
  })
}, 5000);
function notifyMe() {
  if (Notification.permission !== "granted")
    Notification.requestPermission();
  else {
    var notification = new Notification('Tinder', {
      icon: 'http://sitecampaign.com/wp-content/themes/sitecampaign-sage//dist/images/icon_1.svg',
      body: "You have a new match!",
    });
  }

}