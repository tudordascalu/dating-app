<form class="signup-form">
    <div class="circle">
        <img class="profile-image" onerror="handleError(this)" style="height:100%;" src="./assets/images/user-icon.png">
    </div>
    <div class="input-group">
        <label>First Name</label>
        <input required type="text" name="nameField" id="nameField" maxlength="20">
    </div>
    <div class="input-group">
        <label>Last Name</label>
        <input required type="text" name="lastNameField" id="lastNameField" maxlength="20">
    </div>
    <div class="input-group">
        <label>Age</label>
        <input required type="number" name="ageField" id="ageField" maxlength="20">
    </div>
    <div class="input-group">
        <label>Image Source</label>
        <input required type="text" name="imageField" id="imageField">
    </div>
    <button type="submit" class="btnSignup">Signup</button>
</form>