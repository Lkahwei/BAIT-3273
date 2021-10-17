<?php
/**
 *Template Name: Register Page
 */
 
 get_header();
?>
<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<style>
	.form_wrapper {
		/* margin: auto; */
		border: 1px solid #ccc;
        min-width: 100%;
	}
	.inputBox{
        margin-left:5%;
		width:100%;
       

	}
	.clearfix {
		border: 1px solid #fff;
		width:90%;
        margin-left:1%;
	}
    .button {
        width:100%;
        margin-left:5%;
        margin-bottom: 10px;
    }

	.form{
		width:100%;

	}
</style>
</head>
<body>
<?php 	
	$counter_user = 0;
	global $wpdb;
	$query = $wpdb->prepare("SELECT userName FROM user");
	$result_user = $wpdb->get_results($query);
	foreach ($result_user as $row) {
		$counter_user += 1;
	}
	$counter_user += 1;
?>
<script>
var check = function() {
  if (document.getElementById('password').value ==
    document.getElementById('rptPassword').value) {
    document.getElementById('message').style.visibility = 'visible';
    document.getElementById('message').style.color = 'green';
    document.getElementById('message').innerHTML = 'Matched';
    document.getElementById("registerButton").disabled = false;
    } else {
    	document.getElementById('message').style.visibility = 'visible';
    	document.getElementById('message').style.color = 'red';
    	document.getElementById('message').innerHTML = 'Not Matched!';
		document.getElementById("registerButton").disabled = true;	
    }
}

var checkValidate = function() {
    var checkBox = document.getElementById("checkbox1");
    if (checkBox.checked == true){
        document.getElementById("registerButton").disabled = false;
        document.getElementById('checkBoxMessage').style.visibility = 'visible';
        document.getElementById('checkBoxMessage').style.color = 'green';
        // document.getElementById('checkBoxMessage').innerHTML = 'Checked!';
    } else {
        document.getElementById("registerButton").disabled = true;
        document.getElementById('checkBoxMessage').style.visibility = 'visible';
    	document.getElementById('checkBoxMessage').style.color = 'red';
    	// document.getElementById('checkBoxMessage').innerHTML = 'Not Checked!';
    }
}
</script>

<div class="form_wrapper">
  <div class="form_container">
    <div style="text-align: center;" class="title_container">
      <h2>User Registration</h2>
    </div>
    <div class="row clearfix">
      <div class="form">
        <form method="POST" >
          <div class="input_field">
		  	<div class="label">
			  <label style="margin-left:4%" class="col-md-4 control-label">Email Address:</label>
			</div>
            <input style="margin-bottom:10px;" class="inputBox" type="email" name="userEmail" placeholder="Email" required autofocus/>
          </div>
          <div class="input_field">
		  	<div class="label">
			  <label style="margin-left:4%" class="col-md-4 control-label">Password:</label>
			</div>
            <input style="margin-bottom:10px;" class="inputBox" type="password" name="userPassword" id="password" minlength="8" placeholder="Password" onkeyup='check();' required/>
          </div>
          <div class="input_field">
		  	<div class="label">
			  <label style="margin-left:4%" class="col-md-4 control-label">Repeat Password:</label>
			</div>
            <input class="inputBox" type="password" name="rptUserPassword" id="rptPassword" minlength="8" placeholder="Re-type Password" onkeyup='check();' required/>
          </div>
          <div style="margin-left:5%; margin-bottom:10px;" id="message"></div>
		  <div class="input_field">
		  	<div class="label">
			  <label style="margin-left:4%" class="col-md-4 control-label">Full Name:</label>
			</div>	
                <input style="margin-bottom:10px;" class="inputBox" type="text" name="userName" placeholder="Full Name" required/>
          </div>
		  <div class="input_field">
		  	<div class="label">
			  <label style="margin-left:4%" class="col-md-4 control-label">Gender:</label>  
			</div>	
				<select class="inputBox" name="userGender">
                  <option value="M">Male</option>
                  <option value="F">Female</option>
                </select>
          </div>
            <div style="margin-top:10px; margin-bottom:10px" class="input_field checkbox_option inputBox">
            	<input type="checkbox" id="checkbox1" name="checkbox1" onclick="checkValidate();"/>
    			<label for="checkbox1">I agree with 
                <a href="#"  style="color: #ebcb28; font-weight:bold;" >
                terms and conditions </a> </label>
            </div>
            <div style="margin-left:5%" id="checkBoxMessage"></div>
          <input class="button" id="registerButton" type="submit" value="Register" name="register">
        </form>
      </div>
    </div>
  </div>
</div>

<?php 	
	$email_exist = false;
	echo $email_exist;
	if (isset($_POST["register"])) {
		$email = $_POST['userEmail'];
		$password = $_POST['userPassword'];
		$fullName = $_POST['userName'];
		$gender  = $_POST['userGender'];
		
		$query = $wpdb->prepare("SELECT userEmail FROM user");
		$result_user_email = $wpdb->get_results($query);
		foreach ($result_user_email as $row) {
			if(strtolower($email) == strtolower($row->userEmail)){
				$email_exist = true;
				break;
			}
		}
		if($email_exist == false){
			if(strlen(strval($counter_user)) == 2){
				$userID = "U0".$counter_user;
			}else{
				$userID = "U".$counter_user;
			}
			$table = 'user';
			$date = date_format(date_create("2013-03-15"),"Y-m-d");
			$table_aut = 'authenticationuser';
			$table_cart = 'shoppingCart';
			$data = array('userID' => $userID, 'userName' => $fullName, 'userGender' => $gender, 'userEmail' => $email);
			$data_aut = array('id' => $userID, 'email' => $email, 'password' => $password, 'type' => 'User');
			$data_shoppingCart = array('cartID' => $userID, 'userID' => $userID, 'createdAt' => $date);
			$wpdb->insert($table, $data);
			$wpdb->insert($table_aut, $data_aut);
			$wpdb->insert($table_cart, $data_shoppingCart);
			$url = "https://laixinyi.azurewebsites.net/login/";
			redirect($url);
		}
		else{
			echo '<script>alert("Email exists in Database, please enter another email.")</script>';	
		}
		
	}
?>

</body>
</html>

<?php
get_footer();