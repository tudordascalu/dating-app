$(".signup-form").on("submit", function (event) {
  event.preventDefault();
  var formData = new FormData($(this)[0]);
  sReqType = 'signup';
  apiPostForm(formData, sReqType)
  .then(data => {
    if(!data.data) {
      Materialize.toast("Make sure you filled up the form!", 3000);
      return;
    }
    showPage("login-page");
  })
    .catch(error => {
      Materialize.toast('Could not sign you up!', 3000);
    });;
});

$(".login-form").on("submit", function (event) {
  event.preventDefault();
  var formData = new FormData($(this)[0]);
  sReqType = 'login';
  apiPostForm(formData, sReqType)
  .then(data => {
    if(data.code == 403) {
      Materialize.toast("Please verify your account", 3000);
      return;
    }
    if(!data.data) {
      Materialize.toast("Username or password is incorrect", 3000);
      return;
    }
    
    localStorage.setItem('USER_DATA', JSON.stringify(data.data))
    
    if(data.status == 201) {
      showPage('admin-page');
      return;
    }
    getLocation();
    showPage('tinder-page');
    $('.navbar-container').show();
  })
    .catch(error => {
      Materialize.toast("Username or password is incorrect", 3000);
    });;
});

function onLike(response) {
  const id = verifyAuth();
  const sLikeId = JSON.parse(localStorage['TINDER_USER_DATA']).id;
  apiLike(id, sLikeId, response ).then(data => {
    showPage('tinder-page');
  }).catch(error => {
  })
}

function onAdminSave() {
  let aUsers = JSON.parse(localStorage.getItem('USERS'));
  const iId = verifyAuth();
  apiAdminSaveUsers(iId, aUsers).then(data => {
    Materialize.toast('Saved changes', 3000);
  }).catch(error => {
  })
}

function onLogout() {
  localStorage.removeItem('USER_DATA');
  const id = verifyAuth();
  apiLogout(id).then(data => {
    showPage('login-page');
    $('.navbar-container').hide();
  }).catch(error => {
    showPage('login-page');
    $('.navbar-container').hide();
  })
}

function onDeleteUser(iId) {
  let aUsers = JSON.parse(localStorage.getItem('USERS'));
  let iIndex = -1;
  for(i = 0; i < aUsers.length; i++) {
    if(aUsers[i].id == iId) {
      iIndex = i;
    }
  }
  if(iIndex > -1) {
    aUsers.splice(iIndex, 1);
    localStorage.setItem('USERS', JSON.stringify(aUsers));
    initializeTable(aUsers);
  }
}

function getLocation() {
  if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(saveNewPosition);
  } else {
      x.innerHTML = "Geolocation is not supported by this browser.";
      return;
  }
}
function saveNewPosition(position) {
  const id = verifyAuth();
  apiSaveLocation(id, position.coords.latitude, position.coords.longitude).then(data => {
  }).catch(error => {
  })
  
}

function seeLocation(sLat, sLong) {
  var mapProp= {
    center:new google.maps.LatLng(sLat,sLong),
    zoom:15,
  };
  var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);

  var marker = new google.maps.Marker({
    position: new google.maps.LatLng(sLat,sLong),
    map: map,
    title: 'Hello World!'
  });
  
  $('#modal1').modal('open');
}