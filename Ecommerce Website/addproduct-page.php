<?php
/**
 *Template Name: Add Product 
 */
 
 get_header(custom);
?>
<?php
session_start();
?>

<?php
global $wpdb;

if (!isset($_SESSION["staffID"])) {
    header("Location: https://laixinyi.azurewebsites.net/login/");
    exit();
}
	
	$counter = 0;

	$query = $wpdb->prepare("SELECT PRODUCTID FROM PRODUCT");
	$product = $wpdb->get_results($query);

	foreach ($product as $row) {
		$counter += 1;
	}
	$counter += 1;
?>

<?php
    echo '<form name = "form1" method = "post" enctype = "multipart/form-data" >';
        echo '<div class = "container">';
            echo '<table cellspacing="0" cellpadding="0">';
                echo '<tr>';
                    echo '<td>';
                        echo '<label>Product Name</label>';
                    echo '</td>';
                    echo '<td>';
                        echo '<label>:</label>';
                    echo '</td>';
                    echo '<td>';
                        echo '<input type = "text" name = "pname" value = "" required/>';
                    echo '</td>';
                echo '</tr>';

                echo '<tr>';
                echo '<td>';
                    echo '<label>Category</label>';
                echo '</td>';
                echo '<td>';
                    echo '<label>:</label>';
                echo '</td>';
                echo '<td>';
                    echo '<select name="category">';
                        echo '<option value="C001">Baju Kurung</option>';
                        echo '<option value="C002">Women Muslim Wear</option>';
                        echo '<option value="C003">Men Muslim Wear</option>';
                    echo '</select>';
                echo '</td>';
            echo '</tr>';

                echo '<tr>';
                    echo '<td>';
                        echo '<label>Product Size</label>';
                    echo '</td>';
                    echo '<td>';
                        echo '<label>:</label>';
                    echo '</td>';
                    echo '<td>';
                        echo '<select name="size">';
                        
                            echo '<option value="L">L</option>';
                            echo '<option value="XL">XL</option>';
                            echo '<option value="XXL">XXL</option>';
                            echo '<option value="XXXL">XXXL</option>';
                        echo '</select>';
                    echo '</td>';
                echo '</tr>';

                echo '<tr>';
                    echo '<td>';
                        echo '<label>Product Price</label>';
                    echo '</td>';
                    echo '<td>';
                        echo '<label>:</label>';
                    echo '</td>';
                    echo '<td>';
                        echo '<input type = "text" name = "price" value = "" required/>';
                    echo '</td>';
                echo '</tr>';

                echo '<tr>';
                    echo '<td>';
                        echo '<label>Quantity To Be Added</label>';
                    echo '</td>';
                    echo '<td>';
                        echo '<label>:</label>';
                    echo '</td>';
                    echo '<td>';
                        echo '<input type = "text" name = "qty" value = "" required/>';
                    echo '</td>';
                echo '</tr>';

                echo '<tr>';
                    echo '<td>';
                        echo '<label>Product Image</label>';
                    echo '</td>';
                    echo '<td>';
                        echo '<label>:</label>';
                    echo '</td>';
                    echo '<td>';
                        echo '<input type="file" name="myimage">';
                    echo '</td>';
                echo '</tr>';

                
            echo '</table>';
            echo '<input type="submit" id="Submit" value="Submit" name="Submit">';
        echo '</div>';
    echo '</form>';

    $imagename=$_FILES["myimage"]["name"]; 

    //Get the content of the image and then add slashes to it 
    $imagetmp=addslashes (file_get_contents($_FILES['myimage']['tmp_name']));


    if(strlen(strval($counter)) == 2){
        $productID = "P0".$counter;
    }else{
        $productID = "P".$counter;
    }

    if (isset($_POST["Submit"])) {
        $newproductID = $productID;
        $table = 'PRODUCT';

        $productname = $_POST['pname'];

        global $wpdb;

        $product = $wpdb->get_results("SELECT PRODUCTNAME FROM PRODUCT");

        foreach($product as $row){
            if($productname == $row -> PRODUCTNAME){
                $checking = 1; 
            }
        }
        
        if ($checking != 1){
            ?>
            <script>

            alert("Product added successfully");
            </script>
            <?php
            $data = array('PRODUCTID' => $newproductID , 'CATEGORYID' => $_POST['category'], 'SIZE' => $_POST['size'], 'PRODUCTNAME' =>  $_POST['pname'], 'PRODUCTPRICE' => $_POST['price'], 'QUANTITYAVAILABLE' => $_POST['qty'], 'PRODUCTSTATUS' => 'Available', 'IMAGELINK' =>$imagename );
            $wpdb->show_errors();
            $wpdb->insert($table, $data);

        }
        else { ?>

            <script>

            alert("Product exist in the database already");
            </script>

        <?php

        }


    }

?>


<style>
table, tr, td{
    border: none;
    border-width: 0px ;

}
</style>


<?php
get_footer();
?>