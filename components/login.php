<form class="login-form">
    <div class="circle">
        <img class="profile-image" onerror="handleError(this)" style="height:100%;" src="./assets/images/user-icon.png">
    </div>
    <div class="input-group">
        <label>Email</label>
        <input required type="email" name="emailFied" id="emailField" maxlength="20">
    </div>
    <div class="input-group">
        <label>Password</label>
        <input required type="pass" name="passField" id="passField" maxlength="20">
    </div>
    <button type="submit" class="btnLogin">Login</button>
</form>