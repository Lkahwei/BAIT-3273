<?php
/**
 *Template Name: Staff Main Dashboard Page
 */
 
 get_header(custom);
?>
<?php
	session_start();
?>

<!DOCTYPE html>
<html>
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

	#logOut {
		display: block;
		padding: 10px;
		background: #212121;
		border: none;
		width: 100%;
		outline: none;
		cursor: pointer;
	}

	#logOut:hover
	{
		background-color: #00c24b;
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

	input {
		width: 75%;
	}

	.txt{
		margin-right: 0;
		margin-top: 5px;
		
	}

	.accountBigTitle{
		margin:0;
	}

	.column_order {
		box-sizing: border-box;
		float: left;
		width: 100%;
		padding: 10px;
		background-color:#aaa;
		border: 1px solid #fff;
	}

	.addressInput {	
		height: 10%;
  		width: 100%;
 		box-sizing: border-box;
	}

	.label {
		min-width: 30px;
	}

	#btnUpdate {
		width: 100%;
		margin-top: 10px;
	}

</style>

<body>
	<?php

		$staffExist = get_staff_logged_in();
		if($staffExist == true){
			$staffID = $_SESSION["staffID"];
			global $wpdb;
			$query = $wpdb->prepare("SELECT staffName, staffGender, staffEmail FROM staff where staffID = %s", $staffID);
			$result_staff = $wpdb->get_results($query);

			foreach ($result_staff as $row2) {
				$staffName = $row2->staffName;
				$staffGender = $row2->staffGender;
				$staffEmail = $row2->staffEmail;
			}
		?>

		<div class="tabs" style="width:100%">
			<div class="tabs__sidebar">
				<button class="tabs__button" data-for-tab="1">Dashboard</button>
				<button class="tabs__button" data-for-tab="2">Account Details</button>
				<form method="POST"> 
					<input class="tabs_button" type="submit" name="logOut" value="Log Out" id="logOut">
				</form>
			</div>

			<?php
				if(isset($_POST['logOut'])){
					unset($staffID);
					$_SESSION["staffID"] = $staffID;
					$url = "https://laixinyi.azurewebsites.net/login/";
					redirect($url);
				}
			?>

		

			<div class="tabs__content" data-tab="1" style="font-size: 18px;">
				<div>
					<div>Hello <b><?php echo $staffName ?></b></div>
                    
                    
					<div>From your account dashboard you can <a href="https://laixinyi.azurewebsites.net/staff-change-password/"><u>edit your password</u></a>.</div>
				</div>
			</div>

			<div class="tabs__content" data-tab="2" style="width:100%">
				<form method="POST">
					<div class="accountBigTitle" style="font-size: 32px"><b>Account Details</b></div>
					<div class="txt">
						<div class="label">
							<label for="fullName" class="entypo-fullName">Full Name*:</label>
						</div>
						<input class="addressInput" id="fullName" type="text" name="staffFullName" placeholder="Full Name" value="<?php echo $staffName;?>" required/>
					</div>
					<div class="txt">
						<div class="label">
							<label for="emailAddress" class="entypo-email">Email Address*: </label>
						</div>
						<input class="addressInput" id="emailAddress" type="email" name="staffEmail" placeholder="Email Address" value="<?php echo $staffEmail;?>" required/>
					</div>
					<div class="label">
						<label for="gender" class="entypo-gender">Gender: </label>
					</div>
					<select class="addressInput" id="gender" name="staffGender"  required>
						<?php if($staffEmail == "M") {
							?><option value="M" selected>M</option>
							<option value="F">F</option>
						<?php } else {
							?><option value="M">M</option>
							<option value="F" selected>F</option>
						<?php } ?>
					</select>
				<input type="submit" id="btnUpdate" value="Update" style="margin-top:10px;width:100%;" name="UpdateDetails">
				</form>
				<?php
					if (isset($_POST["UpdateDetails"])) {
						$newStaffID = $staffID;
						$table = 'staff';
						$data = array('staffName' =>  $_POST['staffFullName'], 'staffGender' => $_POST['staffGender'], 'staffEmail' => $_POST['staffEmail']);
						$where = array('staffID' => $newStaffID);
						$wpdb->update($table, $data, $where);
						$table = 'authenticationstaff';
						$userEmail = $_POST['staffEmail'];
						$data = array('email' => $userEmail);
						$where = array('id' => $newStaffID);
						$wpdb->update($table, $data, $where);					
						
				?>
                		<script>
							alert("Staff details updated successfully. Now Reloading.")
  							location.replace("https://laixinyi.azurewebsites.net/staff-main-dashboard/");
						</script>
				<?php
					}
				?>
			</div>
		</div>
	<?php	
	}
	else {
		?><div>
		<h2>Hi, You haven't Login Yet.</h2>
		<p>Already Have an Account? Click <a style="color:blue"; href="https://laixinyi.azurewebsites.net/login/"><b><u>Here</u></b></a> to log in.</p>
		
		</div>

	<?php
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
				tabsContainer.querySelector(".tabs__sidebar .tabs__button").click();
			});
		});
	</script>
</body>
</html>

<?php
get_footer();
?>