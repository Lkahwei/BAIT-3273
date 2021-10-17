<?php
/**
 *Template Name: Staff Change Password Page
 */
 
 get_header(custom);
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
        $staffID = $_SESSION["staffID"];
        if (!isset($_SESSION["staffID"])) {
            header("Location: https://laixinyi.azurewebsites.net/login/");
            exit();
        }
        global $wpdb;
        $query = $wpdb->prepare("SELECT staffName, staffGender, staffEmail FROM staff where staffID = %s", $staffID);
        $result_authentication_staff = $wpdb->get_results( "SELECT id, email, password FROM authenticationstaff");
        $result_staff = $wpdb->get_results($query);

        foreach ($result_staff as $row2) {
			$staffName = $row2->staffName;
			$staffGender = $row2->staffGender;
			$staffEmail = $row2->staffEmail;
		}

        foreach ($result_authentication_staff as $row2) {
			if($row2->id == $staffID){
                $password = $row2->password;
            }
		}
?>

        <div class="form" style="width:70%">
        <div class="accountBigTitle" style="font-size: 32px;text-align: center;"><b>Change Password</b></div>
        
                <div class="passwordContainer">
                    <form method="POST">
                        <div><h3 class="accountBigTitle">Change Password</h3></div>
                        <div class="txt">
                            <div class="label">
                                <label for="oldPassword" class="entypo-old-password">Old Password*:</label>
                            </div>
                            <input class="addressInput" id="oldPassword" type="password" name="staffOldPassword" placeholder="Old Password*" minlength="8" style="width:99%" required />
                            <i class="far fa-eye" id="togglePassword"  style="margin-left: -50px; cursor: pointer;"></i>
                        
                        </div>
                        <div class="txt">
                            <div class="label">
                                <label for="newPassword" class="entypo-new-password">New Password*:</label>
                            </div>
                            <input class="addressInput" id="newPassword" type="password" name="staffNewPassword" minlength="8" placeholder="New Password*" onkeyup='check();' style="width:99%" required/>
                            <i class="far fa-eye" id="togglePassword2"  style="margin-left: -50px; cursor: pointer;"></i>
                        </div>
                        <div class="txt">
                            <div class="label">
                                <label for="newConfirmPassword" class="entypo-new-password">Confirm New Password*:</label>
                            </div>
                            <input class="addressInput" id="newConfirmPassword" type="password" name="staffNewConfirmPassword" minlength="8" placeholder="Confirm New Password*" onkeyup='check();' style="width:99%" required/>
                            <i class="far fa-eye" id="togglePassword3"  style="margin-left: -50px; cursor: pointer;"></i>
                        
                            <div id="message"></div>
                        </div>
                        
                        <input type="submit" class="updateButton" id="updatePasswordButton" value="Update" name="updatePassword" onclick='check();'>
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
				$newstaffID = $staffID;
				$table = 'authenticationstaff';

				$password = $_POST['staffNewPassword'];

                $oldPassword = $_POST['staffOldPassword'];
                $staffID = $_SESSION["staffID"];

                $staff = $wpdb ->prepare("SELECT `password`    
                                         FROM authenticationstaff
                                         WHERE id = %s", $staffID);

                $staffPasswordResult = $wpdb->get_results($staff);  
                $dbPassword = $staffPasswordResult[0]->password; 
                
                if($oldPassword == $dbPassword){

                    $data = array('password' => $password);
                    $where = array('id' => $newstaffID);
                    $wpdb->update($table, $data, $where); ?>

                    <script>
                        alert("Staff Password updated successfully. Now Reloading.")
                        // location.replace("https://laixinyi.azurewebsites.net/user-account-details/");
				    </script>
                <?php
                }
                else {
                    
                    ?> 

                    <script>
                        alert("Staff Password Unsuccessfully");
				    </script>
                <?php
                }

				
              
			}

    
    ?>       

            
              

</body>
</html>

<?php
get_footer();?>