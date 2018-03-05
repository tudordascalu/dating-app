<form enctype="multipart/form-data" class="login-form col s12">
    <!-- <div class="circle">
        <img class="profile-image" onerror="handleError(this)" style="height:100%;" src="./assets/images/user-icon.png">
    </div> -->
    <div style="margin-top: 80px" class="row">
        <div class="input-field col s12">
            <input name="email" type="email">
            <label for="email">Email</label>
        </div>
    </div>
    <div class="row">
        <div class="input-field col s12">
            <input name="password" type="password">
            <label for="password">Password</label>
        </div>
    </div>
    <div class="flex-button row">
        <button type="submit" style="width:100%" class="btn">Login</button>
    </div>
    <div class="row" style="font-size:18px">Don't have an account? <a onclick="showPage('signup-page')">SIGN UP</a></div>
</form>