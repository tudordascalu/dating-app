<form enctype="multipart/form-data" class="signup-form">
    <div class="circle">
        <img class="profile-image" onerror="handleError(this)" style="height:100%;" src="./assets/images/user-icon.png">
    </div>
    <div class="input-group">
        <label>Email</label>
        <input required type="email" name="email" id="email">
    </div>
    <div class="input-group">
        <label>Password</label>
        <input required type="password" name="password" id="password">
    </div>
    <div class="input-group">
        <label>Image Source</label>
        <input required type="text" name="imageField" id="imageField">
    </div>
    <div class="input-group">
        <label>First Name</label>
        <input required type="text" name="firstName" id="firstName" maxlength="20">
    </div>
    <div class="input-group">
        <label>Last Name</label>
        <input required type="text" name="lastName" id="lastName" maxlength="20">
    </div>
    <div class="input-group">
        <label>Age</label>
        <input required type="number" name="age" id="age" maxlength="20">
    </div>
    <!-- <div class="input-group">
        <label>Image Source</label>
        <input required type="text" name="imageField" id="imageField">
    </div> -->
    <div class="file-field input-field">
      <div class="btn">
        <span>File</span>
        <input type="file">
      </div>
      <div class="file-path-wrapper">
        <input class="file-path validate" type="text">
      </div>
    </div>
    <button type="submit" class="btnSignup">Signup</button>
</form>