<?php
				if(isset($_POST['logOutButton'])){
					unset($userID);
					$_SESSION["userID"] = $userID;
					$url = "https://laixinyi.azurewebsites.net/login/";
					redirect($url);
				}
			?>

<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Botiga
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
	
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">

	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'botiga' ); ?></a>

	<?php echo '<div
    style="width: 100%;">
    <div style="background-color: #c1c1c1;height: 78px;padding: 0px 40px 0 40px;display: flex;align-items: center;justify-content: space-between;">
        <div style="width: 100px;height: 50px;">
            <a href="https://laixinyi.azurewebsites.net/homescreen-staff-2/">
                <img src="https://laixinyi.azurewebsites.net/wp-content/uploads/2021/09/logo-removebg-preview.png"
                    alt="">
            </a>
        </div>

        <div style="width: 80%;display: flex;justify-content:center">
            <a href="https://laixinyi.azurewebsites.net/homescreen-staff-2/">
                <div class="hoverLink" style="font-weight: bold;font-size: 18px;">
                    Staff Home
                </div>
            </a>
            <a href="https://laixinyi.azurewebsites.net/staff-main-dashboard/">
                <div class="hoverLink" style="font-weight: bold;font-size: 18px;margin-left: 40px;">
                    Staff Account
                </div>

            </a>
            
            '
            ?>

            
            <?php 

            echo'
         

        </div>
        <div style="width: 5%;">

        </div>
    </div>
</div>' ?>
<style>
    .hoverLink {
        color: #212121;
        cursor: pointer;
    }

    .hoverLink:hover {
        color: #ff8c00;
    }
</style>
	<?php do_action( 'botiga_page_header' ); ?>
	
	<?php do_action( 'botiga_main_wrapper_start' ); ?>