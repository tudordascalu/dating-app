<form style="margin-top: 300px" enctype="multipart/form-data" class="signup-form col s12">   
    <div class="circle">
        <img class="profile-image" onerror="handleError(this)" style="height:100%;" src="./assets/images/user-icon.png">
    </div>
    <div style="margin-top:40px" class="row">
        <div class="input-field col s12">
            <input name="firstName" id="firstName" type="text" >
            <label for="firstName">First Name</label>
        </div>
    </div>
    <div class="row">
        <div class="input-field col s12">
            <input name="lastName" id="lastName" type="text" >
            <label for="lastName">Last Name</label>
        </div>
    </div>
    <div class="row">
        <div class="input-field col s12">
            <select name="gender">
                <option value="" disabled selected>Gender</option>
                <option value="1">Male</option>
                <option value="2">Female</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="input-field col s12">
            <input name="email" id="email" type="email" >
            <label for="email">Email</label>
        </div>
    </div>
    <div class="row">
        <div class="input-field col s12">
            <input name="password" id="password" type="password" >
            <label for="password">Password</label>
        </div>
    </div>
    <div class="row">
        <div class="input-field col s12">
            <input id="age" name="age" type="number" >
            <label for="age">Age</label>
        </div>
    </div>
    <div class="row">
        <div class="input-field col s12">
          <textarea name="description" class="materialize-textarea"></textarea>
          <label for="description">Short description</label>
        </div>
    </div>
    <div class="row">
        <div class="input-field col s12">
            <select name="interest">
                <option value="" disabled selected>Interested in</option>
                <option value="m">Male</option>
                <option value="f">Female</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="file-field input-field">
            <div class="btn">
                <span>File</span>
                <input type="file" name="image">
            </div>
            <div class="file-path-wrapper">
                <input class="file-path validate" type="text">
            </div>
        </div>
    </div>
    <div class="flex-button row">
        <button type="submit" style="width:100%" class="btn center">Signup</button>
    </div>
    <div class="row" style="font-size:18px;">Already have an account? <a onclick="showPage('login-page')">LOG IN</a></div>
</form>