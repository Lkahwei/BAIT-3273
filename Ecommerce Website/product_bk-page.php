

<?php
/**
 *Template Name: Baju Kurung
 */
 
 get_header();
?>
<?php
	session_start();
?>
<?php
global $wpdb;
$result = $wpdb->get_results( "SELECT DISTINCT P.PRODUCTNAME, C.TYPE, P.PRODUCTSTATUS, P.IMAGELINK
                              FROM PRODUCT P, CATEGORY C 
                              WHERE P.CATEGORYID = C.CATEGORYID AND 
                                    P.CATEGORYID = 'C001'  AND 
                                    P.PRODUCTSTATUS = 'Available' ; " );


echo '<div style="font-weight:bold;font-size:36px;width:100%;text-align:center">Product Category: ' .$result[0]->TYPE.'</div>';
echo '<div style="width:100%;display: flex;align-items: center;flex-wrap: wrap;height: 100%;padding: 10px 10px 10px 10px;">';
$counter = 1 ; 

foreach ($result as $row) {
  
    echo'<div style="width:calc((100% / 3) - 40px); height: 630px; border-radius: 5px;margin-left: 40px;margin-bottom: 50px;display: flex;flex-direction: column;justify-content: space-between;
    align-items: center;box-shadow: 1px 3px 10px;padding: 20px;">';
    
        echo'<div style="width:100% ;height: 100%; display: flex ;flex-direction: column;justify-content: space-between;">';

            echo '<div>' ;
                echo '<div class="img1" style="display: flex; align-items: center; width: 100%; height: 100%; border-radius: 5px;">';
                echo "<img src='" . $row->IMAGELINK. "' alt='error'>";
                echo '</div>';
            echo '</div>';

            echo '<div style="margin-top: 5px; margin-bottom: 10px;">';
            
            echo '<div style="font-size:24px; text-align:center; font-weight: bold;">' . $row->PRODUCTNAME. '</div>';

            // echo '<div style="margin-top: 10px;">Category: ' . $row->TYPE. "</div>";
            echo '</div>';
   
        echo '</div>';
        $productName = $row->PRODUCTNAME ; 
        $addCart = "https://laixinyi.azurewebsites.net/product-details/?counter=".$productName;

        echo '<div style="width:100%" > 
                 <a href="'.$addCart.'">
                 <button class="button Details">More Details</button></div> 
              </a>';

    echo '</div>';
    $counter += 1 ;

}

echo '</div>';

?>
<style>
    .button {
      border: none;
      color: white;
      text-align: center;
      display: inline-block;
      font-size: 16px;
      margin-top: 10px;
      cursor: pointer;
      border-radius: 5px;
      width: 100%; 
      font-family: Arial, Helvetica, sans-serif;
    }
    
    .Details {
      background-color: white; 
      color: black; 
      border: 2px solid #FF8C00;
      width: 100%; 
    }
    
    .Details:hover {
      background-color: #FF8C00;
      color: white;
      border: 2px solid #FF8C00;
      width: 100%; 
    }
    
</style>

<?php
get_footer(); 
?>