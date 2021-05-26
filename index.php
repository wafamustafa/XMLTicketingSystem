<?php
session_start();
//set variables for username and password as empty
$usernameInput = "";
$passwordInput = "";

if(isset($_POST["loginbtn"])){
	//load xml file
	$XMLuser = simplexml_load_file("xml/user.xml");

	//check to see if input in username and password match the xml document
	$usernameInput = $_POST["username"];
	$passwordInput = $_POST["password"];

	//loop through user XML file
	for ($i = 0; $i < sizeof($XMLuser); $i++){
		//if username and password match the the child element in account
		if($XMLuser->account[$i]->username == $usernameInput && $XMLuser->account[$i]->password == $passwordInput){
			//then start session
			
			//start the session under the id attribute
			$data =(string)$XMLuser->account[$i]['id'];
			$_SESSION['userId'] = $data;
			$data2 =(string)$XMLuser->account[$i]['id'];
			$_SESSION['employeeId'] = $data2;

			//depending on the usertype the user will be redirected to appropiate pages
			if($XMLuser->account[$i]['usertype'] == 'employee'){
				//if employee redirect to staff page
				header("Location:staff.php");

			} else if($XMLuser->account[$i]['usertype'] == 'user'){
				//if user redirect to user page
				header("Location:user.php");
				//echo $_SESSION['userId'];

			} else { 
				//if nothing matches echo invalid message
				echo "Please enter valid username or password";
			}
		}
	}
}

?>

<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <title>Wafa's Ticketing System</title>
        <link rel="stylesheet" type="text/css" href="./css/style.css" />
    </head>
    <body>
        <h1>Wafa's Ticketing System</h1>
        <form id="loginform" method="POST" action=""> 
			<!--Username-->
			<div class="loginform_userdiv">
				<label for="username" class="loginform_username">User Name : </label>
				<div class="loginform_username__txtbox">
				  <input type="text" class="form-control" id="username" name="username" />
				</div>
			</div>
            <!--Password-->
			<div class="loginform_passdiv">
				<label for="password" class="loginform_password">Password : </label>
				<div class="loginform_password__txtbox">
				  <input type="password" class="form-control" id="password" name="password" />
				</div>
			</div>
			<!--submit button-->
			<div class="loginform_submit">
				<div>
				 <button type="submit" class="loginform_submit__btn" id="loginbtn" name="loginbtn">Log In</button>
				</div>
			</div>
  
        </form>
    </body>
</html>