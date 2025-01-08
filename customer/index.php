<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS</title>
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/font-awesome.css" rel="stylesheet" type="text/css">
</head>
<body class="login-bg">
    <div class="container">
        <div class="mainBox">
            <div class="centeredBox">
                <!-- Hide the signupBox and display the loginBox -->
                <div class="loginBoxVisibility" id="loginBox" style="display: none;">
                    <div class="loginBox">
                        <div>
                            Already Have an Account?
                        </div>
                        <div class="btnBox">
                            <button class="btn" id="signinBtn">Sign in</button>
                        </div>
                    </div>
                </div>
                <div class="loginForm" id="loginFormm" style="display: block;">
                    <div class="mainLoginBox">
                        <div class="loginVisibility" id="loginVisibility">
                            <div class="loginTitle">
                                Sign in
                            </div>
                            <form action="login.php" method="POST">
                                <div class="loginFormInput">
                                    <div class="input">
                                        <input type="text" name="username" autocomplete="off" placeholder="Email or Username" />
                                    </div>

                                    <div class="input">
                                        <input type="password" name="password" autocomplete="off" placeholder="Password" />
                                    </div>
                                    <div class="btnBox">
                                        <input type="submit" name="submit" value="Login" class="LoginBtn">
                                    </div>
                                </div>
                               
                            </form>
                        </div>
                    </div>
                </div>
                <div class="fakeSignupDiv" id="fakeSignupDiv"></div>
                <div class="fakeSigninDiv" id="fakeSigninDiv"></div>
                <!-- Initially show the signupBox and hide the signupForm -->
                <div class="signupBoxVisibility" id="signupBox" style="display: block;">
                    <div class="signupBox">
                        <div>
                            Don't Have an Account?
                        </div>
                        <div class="btnBox">
                            <button class="btn" id="signupBtn">Sign up</button>
                        </div>
                    </div>
                </div>
                <div class="signupForm" id="signupFormm" style="display: none;">
                    <div class="mainSignupBox">
                        <div class="signupVisibility" id="signupVisibility">
                            <div class="signupTitle">
                                Sign up
                            </div>
                            <form action="signup.php" method="POST">
                            <div class="signupFormInput">
                                <div class="input">
                                    <input type="text" name="susername" autocomplete="off" placeholder="Username" />
                                </div>
                                <div class="input">
                                    <input type="email" name="semail" autocomplete="off" placeholder="Email" />
                                </div>
    
                                <div class="input">
                                    <select id="designation" name="designation" placeholder="Designation">
                                        <option value="#" selected=""> Role </option>
                                        <option value="Admin">Admin</option>
                                        <option value="Student">Student</option>
                                    </select>
                                    <span class="custom-arrow"></span>

                                </div>
                                <div class="input">
                                    <input type="password" name="spassword" autocomplete="off" placeholder="Password" />
                                </div>
                               
                            </div>
                            <div class="btnBox">
                                <input type="submit" name="submit" value="Sign up" class="btn signupBtn">
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
  var signinBtn = document.getElementById("signinBtn")
var signupBtn = document.getElementById("signupBtn")
var loginBox = document.getElementById("loginBox")
var signupBox = document.getElementById("signupBox")
var loginForm = document.getElementById("loginFormm")
var signupForm = document.getElementById("signupFormm")
var signupVisibility = document.getElementById("signupVisibility")
var loginVisibility = document.getElementById("loginVisibility")
var fakeSignupDiv = document.getElementById("fakeSignupDiv")
var fakeSigninDiv = document.getElementById("fakeSigninDiv")
var centerBox = document.getElementsByClassName("centeredBox")[0]

signinBtn.onclick = function () {
  if (window.innerWidth > 1120) {
    signupVisibility.setAttribute("class", "out")
    setTimeout(() => {
      fakeSignupDiv.setAttribute("class", "toLeft")
      signupBox.style.display = "block"
      signupForm.style.display = "none"
    }, 300)

    setTimeout(() => {
      loginBox.style.display = "none"
      loginForm.style.display = "block"
      loginVisibility.setAttribute("class", "inn")
      fakeSignupDiv.setAttribute("class", "")
    }, 1300)
  } else {
    signupVisibility.setAttribute("class", "out")
    setTimeout(() => {
      centerBox.style.flexDirection = "column"
      loginVisibility.setAttribute("class", "inn")
      loginForm.style.display = "block"
      signupForm.style.display = "none"
      signupBox.style.display = "block"
      loginBox.style.display = "none"
    }, 500)
  }
}

signupBtn.onclick = function () {
  if (window.innerWidth > 1120) {
    loginVisibility.setAttribute("class", "outt")
    setTimeout(() => {
      fakeSigninDiv.setAttribute("class", "toRight")
      loginBox.style.display = "block"
      loginForm.style.display = "none"
    }, 300)

    setTimeout(() => {
      signupForm.style.display = "block"
      signupBox.style.display = "none"
      signupVisibility.setAttribute("class", "in")
      fakeSigninDiv.setAttribute("class", "")
    }, 1300)
  } else {
    loginVisibility.setAttribute("class", "out")
    setTimeout(() => {
      signupVisibility.setAttribute("class", "inn")
      centerBox.style.flexDirection = "column-reverse"
      loginForm.style.display = "none"
      signupForm.style.display = "block"
      signupBox.style.display = "none"
      loginBox.style.display = "block"
    }, 500)
  }
}

</script>

</body>
</html>
