<?php
/**
 *Template Name: User Main Dashboard Page
 */
 
 get_header();
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

	#logOutButton {
		display: block;
		padding: 10px;
		background: #212121;
		border: none;
		width: 100%;
		outline: none;
		cursor: pointer;
	}

	#logOutButton:hover
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
		background-color:#cecece;
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

	.submitButton #btnUpdate {
		width: 100%;
		margin-top: 10px;
	}

	#a_order_dashboard{
		color:black;
	}

	#a_order_dashboard:hover{
		opacity:0.7;
	}

</style>
<body>
	<?php
		$userExist = get_user_logged_in();
		if($userExist == true){
			$userID = $_SESSION["userID"];
			global $wpdb;
			$query = $wpdb->prepare( "SELECT orderID, recipientName, userName, orderDate, shipDate, amount, paymentType FROM ordertable o, delivery d, user u where u.userID = %s AND u.userID = o.userID AND o.deliveryID = d.deliveryID order by orderDate DESC", $userID);
			$result_order = $wpdb->get_results($query);
			$query = $wpdb->prepare("SELECT userName, userGender, userEmail, streetName, postCode, 
			city, state FROM user where userID = %s", $userID);
			$result_user = $wpdb->get_results($query);
			
			foreach ($result_user as $row2) {
				$userName = $row2->userName;
				$userGender = $row2->userGender;
				$userEmail = $row2->userEmail;
				$userStreet = $row2->streetName;
				$userPostCode = $row2->postCode;
				$userCity = $row2->city;
				$userState = $row2->state;
			}
		?>
		<div class="tabs" style="width:100%">
			<div class="tabs__sidebar">
				<button class="tabs__button" data-for-tab="1">Dashboard</button>
				<button class="tabs__button" data-for-tab="2">Orders</button>
				<button class="tabs__button" data-for-tab="3">Address</button>
				<button class="tabs__button" data-for-tab="4">Account Details</button>
				<form method="POST">
					<input class="tabs_button" type="submit" name="logOutButton" value="Log Out" id="logOutButton">
				</form>
				
			</div>

			<?php
				if(isset($_POST['logOutButton'])){
					unset($userID);
					$_SESSION["userID"] = $userID;
					$url = "https://laixinyi.azurewebsites.net/login/";
					redirect($url);
				}
			?>

			<div class="tabs__content tabs__content--active" data-tab="1">
				<div style="font-size: 18px;">
					<div>Hello <b><?php echo $userName ?></b></div>
					<div>From your account dashboard you can view your <a href="https://laixinyi.azurewebsites.net/order/"><u>recent orders</u><a> and <a href="https://laixinyi.azurewebsites.net/user-change-password/"><u>edit your password</u></a>.</div>
				</div>
			</div>

			<div class="tabs__content" data-tab="2">
				<?php 
					$counter = 1;
					if(!empty($result_order)) {
						foreach ($result_order as $row) {
							$orderID = $row->orderID;
							$userName = $row->userName;
							$recipientName = $row->recipientName;
							$date = $row->orderDate;
							$amount = $row->amount;
							$url = "https://laixinyi.azurewebsites.net/order-details/?orderID=".$orderID;?>
							<a href="<?php echo $url ?>" id="a_order_dashboard">
								<div class="column_order">
									<div style="font-size: 18px;"><?php echo "Order ID: $orderID"; ?><div style="float:right; font-size: 18px;"><?php echo "Order Date: $date"; ?></div></div>
									<div style="font-size: 18px;"><?php echo "Recipient Name: $recipientName"; ?><div style="float:right; font-size: 18px;"><?php echo ">>"; ?></div></div>
									<div style="font-size: 18px;"><?php echo "Total Amount: RM $amount"; ?></div>
								</div>
							</a>
							
					<?php
						} 
					}else {
						?><div>
							<h2>Hi, You dont have any orders yet</h2>
							<p>Already Have an Account? Click <a style="color:blue"; href="https://laixinyi.azurewebsites.net/product/"><b><u>Here</u></b></a> to view our products.</p>
						</div>

					<?php
					}
					?>
			</div>

			<div class="tabs__content" data-tab="3" style="width:100%" >
				<form method="POST">
					<div class="accountBigTitle" style="font-size: 32px"><b>Address</b></div>
					<div><h5 class="accountSmallTitle">The following address will be used on the checkout page by default.</h5></div>
					<div class="txt">
						<div class="label">
							<label for="streetName" class="entypo-street">Street name:</label>
						</div>
						<input class="addressInput" id="streetName" type="text" name="userStreetName" placeholder="Street Name" value="<?php echo $userStreet;?>"/>
					</div>
					<div class="txt">
						<div class="label">
							<label for="postcode" class="entypo-postcode">Postcode:</label>
						</div>
						
						<input class="addressInput" id="postcode" type="text" name="userPostcode" placeholder="Postcode" value="<?php echo $userPostCode;?>" />
					</div>
					<div class="txt">
						<div class="label">
							<label for="city" class="entypo-city">City:</label>
						</div>
						<input class="addressInput" id="city" type="text" name="userCity" placeholder="City" value="<?php echo $userCity;?>" />
							
					</div>
					<div class="txt">
						<div class="label">
							<label for="state" class="entypo-state">State:</label>
						</div>
						<input class="addressInput" id="state" type="text" name="userState" placeholder="State" value="<?php echo $userState;?>"/>
					</div>
					<div class="submitButton">
						<input type="submit" id="btnUpdate" value="Update" name="Update">
					</div>
				</form>
				<?php
					if (isset($_POST["Update"])) {
						$newuserID = $userID;
						$userStreet = $_POST['userStreet'];
						$userPostCode = $_POST['userPostcode'];
						$userCity = $_POST['userCity'];
						$userState = $_POST['userState'];
						$table = 'user';
						$data = array('userName' =>  $userName, 'userGender' => $userGender, 
						'userEmail' => $userEmail, 'streetName' => $_POST['userStreetName'], 
						'postCode' => $_POST['userPostcode'], 'city' => $_POST['userCity'], 
						'state' => $_POST['userState']);
						$where = array('userID' => $newuserID);
						$wpdb->update($table, $data, $where);
						?>
						<script>
							alert("User details updated successfully. Now Reloading.")
  							location.replace("https://laixinyi.azurewebsites.net/about-me");
						</script>
				<?php		
					}
				?>
			</div>

			<div class="tabs__content"  data-tab="4" style="width:100%">
				<form method="POST" >
					<div class="accountBigTitle" style="font-size: 32px"><b>Account Details</b></div>
					<div class="txt">
						<div class="label">
							<label for="fullName" class="entypo-fullName">Full Name*:</label>
						</div>
						<input class="addressInput" id="fullName" type="text" name="userFullName" placeholder="Full Name" value="<?php echo $userName;?>" required/>
					</div>
					<div class="txt">
						<div class="label">
							<label for="emailAddress" class="entypo-email">Email Address*:</label>
						</div>
						<input class="addressInput" id="emailAddress" type="email" name="userEmail" placeholder="Email Address" value="<?php echo $userEmail;?>" required/>
					</div>
					<div class="label">
						<label for="gender" class="entypo-gender">Gender: </label>
					</div>
					<select class="addressInput" id="gender" name="userGender" required>
						<?php if($userGender == "M") {
							?><option value="M" selected>M</option>
							<option value="F">F</option>
						<?php } else {
							?><option value="M">M</option>
							<option value="F" selected>F</option>
						<?php } ?>
					</select>
				<input type="submit" id="btnUpdate" value="Update" style="width:100%; margin-top:10px;" name="UpdateDetails">
				</form>
				<?php
					if (isset($_POST["UpdateDetails"])) {
						$newuserID = $userID;
						$table = 'user';
						$data = array('userName' =>  $_POST['userFullName'], 
						'userGender' => $_POST['userGender'], 'userEmail' => $_POST['userEmail'], 
						'streetName' => $userState, 'postCode' => $userPostCode, 'city' => $userCity, 
						'state' => $userState);
						$where = array('userID' => $newuserID);
						$wpdb->update($table, $data, $where);
						$table = 'authenticationuser';
						$userEmail = $_POST['userEmail'];
						$data = array('email' => $userEmail);
						$where = array('id' => $newuserID);
						$wpdb->update($table, $data, $where);
				?>
						<script>
							alert("User details updated successfully. Now Reloading.")
  							location.replace("https://laixinyi.azurewebsites.net/about-me");
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
		<p>New User? Click <a style="color:blue"; href="https://laixinyi.azurewebsites.net/register/"><b><u>Here</u></b></a> to register an Account.</p>
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
get_footer(); ?>