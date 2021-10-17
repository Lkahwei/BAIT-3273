<?php
/**
 *Template Name: Main Login Page
 */
 
 get_header();
?>

<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
<style>
	.tabs {
		border: 1px solid #cccccc;
		display: flex;
	}
	
	.tabs__sidebar {
		width: 125px;
		flex-shrink : 0;
		background: #adaaaa;
	}

	.tabs__button {
		display: block;
		padding: 10px;
		background: #212121;
		border: none;
		width: 100%;
		outline: none;
		cursor: pointer;
	}
	
	.tabs__button:active {
		background: #ff8c00;
	}

	.tabs__button:not(:last-of-type) {
		border-bottom: 1px solid #cccccc;
	}

	.tabs__button--active {
		font-weight: bold;
		border-right: 2px solid #009879;
		background: #ff8c00;
	}

	.tabs__content {
		padding: 15px;
		font-size: 0.8rem;
		display: none;
	}

	.tabs__content--active {
		display: block;
	}

	.tabs__content > :first-child {
		margin-top: 0;
	}
	
	.titleLogin {
		text-align: center;
		margin-top: 0;
	}

	form {
		width:100%;
	}

	.inputBox{
		width:100%;
	}

	#email, #password{
	width: 250px;
	margin: 8px 0;
	display: inline-block;
	border: 1px solid #ccc;
	border-radius: 4px;
	box-sizing: border-box;
	}

	#email2, #password2{
	width: 250px;
	margin: 8px 0;
	display: inline-block;
	border: 1px solid #ccc;
	border-radius: 4px;
	box-sizing: border-box;
	}

	/* unvisited link */
	.register_link:link {
		color: #FF8C00;
	}

	/* visited link */
	.register_link:visited {
		color: #FF8C00;
	}

	/* mouse over link */
	.register_link:hover {

	}


</style>
</head>
<body>
<?php
	$getUserLogin = get_user_logged_in();
	$getStaffLogin = get_staff_logged_in();

	if($getUserLogin == true){
		$url = "https://laixinyi.azurewebsites.net/";
		redirect($url);
	}
	elseif($getStaffLogin == true){
		$url = "https://laixinyi.azurewebsites.net/homescreen-staff-2/";
		redirect($url);
	}
	else{?>
		<div style="width:50%;height:50%; margin-left: 25%" class = "tabs">

			<div class="tabs__sidebar">
				<button class="tabs__button" data-for-tab="1">User</button>
				<!-- <button class="tabs__button">User</button> -->
				<button class="tabs__button" data-for-tab="2">Staff</button>
			</div>

			<div class="tabs__content" data-tab="1" style="width:100%;">
			<!-- <div class="tabs__content"style="width:100%;"> -->
				<form method="POST">
					<div><h3 class="titleLogin">User Login</h3></div>
					<div class="txt">
						<input id="email" type="email" name="userEmail" placeholder="User Email" class="inputBox" style="width:95%" required />
							<label for="email" class="entypo-user"></label>
					</div>

					<div class="txt" style="margin-bottom:10px">
						<input id="password" type="password" name="password" placeholder="Password" class="inputBox" minlength="8" style="width:95%" required/>
						<!-- <div style="float:right;margin-top:10px" class="passwordToggleIcon"> -->
							<i class="far fa-eye" id="togglePassword"  style="margin-left: -50px; cursor: pointer;"></i>
						<!-- </div> -->
							
					</div>

					<div class="buttons" style="margin-bottom: 15px">
						<input type="submit" value="Login" name="LoginUser" id="LoginUser"/>
					</div>
				</form>
				<div class="container signin" >
					<p>No account yet? <a style="color: #ebcb28; font-weight:bold;" href="https://laixinyi.azurewebsites.net/register/" class="register_link">Register now!</a></p>
				</div>
			</div>
			<div class="tabs__content" data-tab="2" style="width:100%">
				<form method="POST">
					<div><h3 class="titleLogin">Staff Login</h3></div>
					<div class="txt">
						<input id="email2" type="email" name="staffEmail" placeholder="Staff Email" class="inputBox" style="width:95%" required/>
    						<label for="email2" class="entypo-user"></label>
					</div>
					<div class="txt" style="margin-bottom:10px">
						<input id="password2" type="password" name="password_staff"placeholder="Password" class="inputBox" minlength="8" style="width:95%" required/>
							<i class="far fa-eye" id="togglePassword2" style="margin-left: -50px; cursor: pointer;"></i>	
					</div>
					<div class="buttons">
						<input type="submit" value="Login" name="LoginStaff" id="LoginStaff" />
  					</div>
				</form>
				<div class="container signin" >
					
				</div>
			</div>
		</div>
<?php
	}
?>


		<script>
			const togglePassword = document.querySelector('#togglePassword');
			const password = document.querySelector('#password');

			togglePassword.addEventListener('click', function (e) {
			// toggle the type attribute
			const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
			password.setAttribute('type', type);
			// toggle the eye slash icon
			this.classList.toggle('fa-eye-slash');
			});

			const togglePassword2 = document.querySelector('#togglePassword2');
			const password2 = document.querySelector('#password2');

			togglePassword2.addEventListener('click', function (e) {
			// toggle the type attribute
			const type2 = password2.getAttribute('type') === 'password' ? 'text' : 'password';
			password2.setAttribute('type', type2);
			// toggle the eye slash icon
			this.classList.toggle('fa-eye-slash');
			});
		</script>
	
		<?php
			if (isset($_POST["LoginUser"])) {
				$email = $_POST['userEmail'];
				$password = $_POST['password'];
				global $wpdb;
				$y = false;
				$result_authentication_user = $wpdb->get_results("SELECT email, password FROM authenticationuser");
				$query_user = $wpdb->prepare("SELECT userID, userName, userGender, userEmail, streetName, postCode, city, state FROM user where userEmail = %s", $email);
				$result_user = $wpdb->get_results($query_user);

				if(!empty($result_user)){
					foreach ($result_authentication_user as $row) {
						if($row->email == $email and $row->password == $password){
							$y = true;
							break;
						}
					}
					if($y == true){
						foreach ($result_user as $row2) {
							$userID = $row2->userID;
							$userName = $row2->userName;
							$userGender = $row2->userGender;
							$userEmail = $row2->userEmail;
							$userStreet = $row2->streetName;
							$userPostCode = $row2->postCode;
							$userCity = $row2->city;
							$userState = $row2->state;
						}
						echo "Correct Password Entered!!";
						$_SESSION["userID"] = $userID;
						$url = "https://laixinyi.azurewebsites.net";
						redirect($url);
					}
					else{
						echo '<script>alert("Wrong Password, please enter again!!")</script>';	
					}

				}
				else{
					echo '<script>alert("Email incorrect, please enter again!!")</script>';	
				}
			}
			elseif (isset($_POST["LoginStaff"])) {
				$email = $_POST['staffEmail'];
				$password = $_POST['password_staff'];
				global $wpdb;
				$y = false;
				$result_authentication_staff = $wpdb->get_results( "SELECT email, password FROM authenticationstaff");
				$query_staff = $wpdb->prepare("SELECT staffID, staffName, staffGender, staffEmail FROM staff where staffEmail = %s", $email);
				$result_staff = $wpdb->get_results($query_staff);
				if(!empty($result_staff)){
					foreach ($result_authentication_staff as $row) {
						if($row->email == $email and $row->password == $password){
							$y = true;
							break;
						}
					}
					if($y == true){
						foreach ($result_staff as $row2) {
							$staffID = $row2->staffID;
							$staffName = $row2->staffName;
							$staffGender = $row2->staffGender;
							$staffEmail = $row2->staffEmail;
						}
						$_SESSION["staffID"] = $staffID;
						echo '<script language="javascript">';
						echo 'alert("Logging In..Please Click Ok!!")';
						echo '</script>';
						$url = "https://laixinyi.azurewebsites.net/homescreen-staff-2/";
						redirect($url);
					}
					else{
						echo '<script>alert("Wrong Password, please enter again!!")</script>';	
					}
				}
				else{
					echo '<script>alert("Email incorrect, please enter again!!")</script>';	
				}
			}
			else{
				
			}
		?>
			

	<script>
		function setupTabs () {
			document.querySelectorAll(".tabs__button").forEach(button => {
				button.addEventListener("click", () => {
					const sideBar = button.parentElement;
					const tabsContainer = sideBar.parentElement;
					const tabNumber = button.dataset.forTab;
					const tabToActivate = tabsContainer.querySelector(`.tabs__content[data-tab="${tabNumber}"]`);

					console.log(sideBar);
					console.log(tabsContainer);
					console.log(tabNumber);
					console.log(tabToActivate);

					sideBar.querySelectorAll(".tabs__button").forEach(button => {
						button.classList.remove("tabs__button--active");
					});
	
					tabsContainer.querySelectorAll(".tabs__content").forEach(tab => {
						tab.classList.remove("tabs__content--active");
					});

					button.classList.add("tabs__button--active");
					tabToActivate.classList.add("tabs__content--active");
				});
			});
		}

		document.addEventListener("DOMContentLoaded",  () => {
			setupTabs();
			document.querySelectorAll(".tabs").forEach(tabsContainer => {
				tabsContainer.querySelector(".tabs_sidebar .tabs_button").click();
			});
		});
	</script>

</body>
</html>

<?php
get_footer();?>