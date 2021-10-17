<?php
/**
 *Template Name: User Change Password Page
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
    .form {
		padding: 15px;
		font-size: 0.8rem;
        margin:auto;
	}


    .passwordContainer, .detailsContainer{
        border: 1px solid #ccc;
        padding: 10px;
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .addressInput {	
		height: 10%;
  		width: 100%;
 		box-sizing: border-box;
	}

    .updateButton{
		width: 100%;
		margin-top: 10px;
	}

    .accountBigTitle{
        margin-top:15px;
        margin-bottom: 15px;
    }
</style>
</head>
<body>
<?php 
    $getUserLogin = get_user_logged_in();
    if($getUserLogin == false){
        $url = "https://laixinyi.azurewebsites.net/";
		redirect($url);
    } else{
        $userID = $_SESSION["userID"];
        global $wpdb;
        $query = $wpdb->prepare("SELECT userName, userGender, userEmail, streetName, postCode, city, state FROM user where userID = %s", $userID);
        $result_authentication_user = $wpdb->get_results( "SELECT id, email, password FROM authenticationuser");
        $result_user = $wpdb->get_results($query);
        foreach ($result_user as $row2) {
			$userName = $row2->userName;
			$userGender = $row2->userGender;
			$userEmail = $row2->userEmail;
		}

        foreach ($result_authentication_user as $row) {   
            if($row->id == $userID){
                $password = $row->password;
            } 
		}
        
?>

        <div class="form" style="width:70%">
        <div class="accountBigTitle" style="font-size: 32px;text-align: center;"><b>Change Password</b></div>
            
                <div class="passwordContainer" >
                    <form method="POST">
                         <div class="txt">
                            <div class="label">
                                <label for="oldPassword" class="entypo-old-password">Old Password*:</label>
                            </div>
                            <input class="addressInput" id="oldPassword" type="password" name="userOldPassword"  placeholder="Old Password*" onkeyup='checkPass();' minlength="8" style="width:99%" required />
                            <i class="far fa-eye" id="togglePassword"  style="margin-left: -50px; cursor: pointer;"></i>
                        </div>
                        <div id="msg"></div>

                        <div class="txt">
                            <div class="label">
                                <label for="newPassword" class="entypo-new-password">New Password*:</label>
                            </div>
                            <input class="addressInput" id="newPassword" name="userNewPassword" type="password" minlength="8" placeholder="New Password*" onkeyup='check();' style="width:99%"required/>
                            <i class="far fa-eye" id="togglePassword2"  style="margin-left: -50px; cursor: pointer;"></i>
                        </div>
                        <div class="txt">
                            <div class="label">
                                <label for="newConfirmPassword" class="entypo-new-password">Confirm New Password*:</label>
                            </div>
                            <input class="addressInput" id="newConfirmPassword"  name="userNewConfirmPassword" type="password" minlength="8" placeholder="Confirm New Password*" style="width:99%" onkeyup='check();' required/>
                            <i class="far fa-eye" id="togglePassword3"  style="margin-left: -50px; cursor: pointer;"></i>
                            <div id="message"></div>
                        </div>
                        
                        <input type="submit" class="updateButton" id="updatePasswordButton" value="Update" name="updatePassword" onclick='check();disabledOrNot();'>
                    </form>
				</div>
			
        </div>

        <script>
            var check = function() {
                if (document.getElementById('newPassword').value ==
                    document.getElementById('newConfirmPassword').value) {
                    document.getElementById('message').style.visibility = 'visible';
                    document.getElementById('message').style.color = 'green';
                    document.getElementById('message').innerHTML = 'matching';
                    document.getElementById("updatePasswordButton").disabled = false;

                } else {
                        document.getElementById('message').style.visibility = 'visible';
                        document.getElementById('message').style.color = 'red';
                        document.getElementById('message').innerHTML = 'not matching';
                        document.getElementById("updatePasswordButton").disabled = true;
                } 
                }


            const togglePassword = document.querySelector('#togglePassword');
			const password = document.querySelector('#oldPassword');

			togglePassword.addEventListener('click', function (e) {
			// toggle the type attribute
			const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
			password.setAttribute('type', type);
			// toggle the eye slash icon
			this.classList.toggle('fa-eye-slash');
			});

            const togglePassword2 = document.querySelector('#togglePassword2');
			const password2 = document.querySelector('#newPassword');

			togglePassword2.addEventListener('click', function (e) {
			// toggle the type attribute
			const type = password2.getAttribute('type') === 'password' ? 'text' : 'password';
			password2.setAttribute('type', type);
			// toggle the eye slash icon
			this.classList.toggle('fa-eye-slash');
			});

            const togglePassword3 = document.querySelector('#togglePassword3');
			const password3 = document.querySelector('#newConfirmPassword');

			togglePassword3.addEventListener('click', function (e) {
			// toggle the type attribute
			const type = password3.getAttribute('type') === 'password' ? 'text' : 'password';
			password3.setAttribute('type', type);
			// toggle the eye slash icon
			this.classList.toggle('fa-eye-slash');
			});
        </script>

     
        <?php	
			
            if (isset($_POST["updatePassword"])) {
				$newuserID = $userID;
				$table = 'authenticationuser';

				$password = $_POST['userNewPassword'];

                $oldPassword = $_POST['userOldPassword'];
                $userID = $_SESSION["userID"];

                $user = $wpdb ->prepare("SELECT `password`    
                                         FROM authenticationuser
                                         WHERE id = %s", $userID);

                $userPasswordResult = $wpdb->get_results($user);  
                $dbPassword = $userPasswordResult[0]->password; 

                
                if($oldPassword == $dbPassword){

                    $data = array('password' => $password);
                    $where = array('id' => $newuserID);
                    $wpdb->update($table, $data, $where); ?>

                    <script>
                        alert("User Password updated successfully. Now Reloading.")
                        location.replace("https://laixinyi.azurewebsites.net/user-account-details/");
				    </script>
                <?php
                }
                else {
                    
                    ?> 

                    <script>
                        alert("User Password Unsuccessfully");
				    </script>
                <?php
                }

				
              
			}

    }
    ?>       
</body>
</html>

<?php
get_footer();?>