<?php

/**
 *Template Name: ProductList
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

function update($id, $wpdb)
{

    echo $id;

    $name = $_POST['name'];
    $qty = $_POST['qty'];

    echo $name;
    echo $qty;
}
global $wpdb;

 if ($_GET['updateid'] && $_GET['prodname'] && $_GET['qty']) {
    $pID = $_GET['updateid'];
    $name = $_GET['prodname'];
    $qty = $_GET['qty'];
    $table = 'PRODUCT';
    $wpdb->update($table, array('QUANTITYAVAILABLE' => $qty, 'PRODUCTNAME' => $name),  array('PRODUCTID' => $pID));
    $wpdb->show_errors();

}else if(isset($_GET['categoryid'])){
    $catid = $_GET['categoryid'];
}

if(isset($_GET['categoryid'])  && isset($_GET['id']) ){

$catid = $_GET['categoryid'];
$pID = $_GET['id'];
$query = $wpdb->prepare("SELECT DISTINCT P.PRODUCTID, P.PRODUCTNAME, C.TYPE, P.QUANTITYAVAILABLE
                                  FROM PRODUCT P, CATEGORY C 
                                  WHERE P.CATEGORYID = C.CATEGORYID AND
                                  P.PRODUCTID = %s", $pID);

$product = $wpdb->get_results($query);


foreach ($product as $row) {

    echo '<div style="display: flex; align-items: center; justify-content: space-around;width:100%">
                        <div style="display:flex;justify-content:space-between;width:100%">
                            <div id="prodID" style="width:30%;background-color:#ff8c00;color:white;padding:10px;text-align:center;border-radius:3px;">
                            ' . $row->PRODUCTID . '
                            </div>
                            
                            <input id="prodName"  name="prodname" style="width:30%;background-color:white; color:#ff8c00;padding:10px;text-align:center;border-radius:3px;" type="text" value="' . $row->PRODUCTNAME . '"  placeholder="' . $row->PRODUCTNAME . '" Required>
                    
                        
                            <input id="prodqty"  name="qty" style="width:30%;background-color:white; color:#ff8c00;padding:10px;text-align:center;border-radius:3px;" value="' . $row->QUANTITYAVAILABLE . '" type="text" placeholder="' . $row->QUANTITYAVAILABLE . '" Required>
                        
                            <div id="updateBtn" style="padding:13px 24px;background-color:#212121;color:white;cursor:pointer">Update</div>
                        </div>
                    </div>
             
           
          ';
}
}




$category = $wpdb -> prepare("SELECT P.PRODUCTID, P.PRODUCTNAME, P.IMAGELINK, P.SIZE, P.PRODUCTPRICE, P.QUANTITYAVAILABLE, C.TYPE
                              FROM  PRODUCT P, CATEGORY C 
                              WHERE P.CATEGORYID = C.CATEGORYID AND 
                                    C.CATEGORYID = %s", $catid); 

$result = $wpdb->get_results($category);

echo '<div style="font-weight:bold;font-size:36px;width:100%;text-align:center"> '.$result[0]->TYPE.' </div>';

echo "<body>";


echo '<table>';
echo '<tr>
       <th>PRODUCT ID</th>
       <th>PRODUCT NAME</th>
       <th>PRODUCT SIZE</th>
       <th>PRODUCT PRICE</th>
       <th>QUANTITY AVAILABLE</th>
       <th style="width: 80px">EDIT</th>
    </tr>';


foreach ($result as $row) {

    $code = $row->PRODUCTID;

    $url = "https://laixinyi.azurewebsites.net/homescreen-staff/staff-product-list/?id=" .$code;


    echo '<tr>
            <td>' . $row->PRODUCTID . '</td>
            <td>
               <div style = "display:flex;align-items:center;">
                    <div style="width: 25%;height: 100%;display: flex;align-items: center;justify-content: center;background-color:white;">
                        <img src=' . $row->IMAGELINK . ' alt="error">
                    </div>  
                    <div style="width: 60%;display: flex;flex-direction: column;">
                    <div id="prodName" style="margin-bottom: 10px;font-size: 25px;font-weight: bold;width: 100%;height: 50%;">
                         ' . $row->PRODUCTNAME . ' 
              
                    </div>
               </div>
            </td> 

            <td>' . $row->SIZE . '</td>
            <td>' . $row->PRODUCTPRICE . '</td>
            <td>' . $row->QUANTITYAVAILABLE . '</td> 

            ';

    echo '<td style="text-align: center;vertical-align:middle"> 
               <a href="https://laixinyi.azurewebsites.net/homescreen-staff/staff-product-list/?categoryid='.$catid."&id=".$code.'">
                <div class="edit" value="'. $catid ."," .$code . '">
                    <img src=" https://laixinyi.azurewebsites.net/wp-content/uploads/2021/09/edit.svg" style="height: 30px;width: 30px;" alt="edit">
                </div>
                </a>
                </td>                 
           
        </tr>';
}



echo '</table>';

?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
//     $(".edit").click(function() {
//     var value = String(this.getAttribute("value"));
//     var cid = value.split(",")[0];
//     var pid = value.split(",")[1];
//     var url = "https://laixinyi.azurewebsites.net/homescreen-staff/staff-product-list/?categoryid=" + cid.trim() + "&id=" + pid.trim(); 
//     console.log(url);
//     // passUrl(url);

//     $.ajax({
//             method: "POST",
//             url: url

//         });
// });





    $("#updateBtn").click(function() {

        // console.log("clicked")

        var qty = parseInt($(this).parent().find('#prodqty').val(), 10);
        var prodName = $(this).parent().find('#prodName').val();
        var prodID = $(this).parent().find('#prodID').html();
        console.log($(this).parent().find('#prodqty').val());
        console.log($(this).parent().find('#prodName').val());

        
        var url = "https://laixinyi.azurewebsites.net/homescreen-staff/staff-product-list/?updateid=" + String(prodID).trim() + "&prodname=" + String(prodName).trim().replace(' ', '%20') + "&qty=" + String(qty).trim();
        passUrl(url, qty, prodName );
        
        location.reload();
        
    });

    function passUrl(url, qty, prodName) {
        // console.log(url);
        $.ajax({
            method: "POST",
            url: url

        });

        alert(prodName + " updated quantity stock to " + qty + " in the shop.");
   
    }
</script>

<style>
    table {
        width: 100%;
        padding-left: 10px;
        margin-top: 20px;
    }

    table,
    th,
    td {
        border: 2px solid black;
        font-family: Arial, Helvetica, sans-serif;
        text-align: center;
        color: black;
        font-size: 20px;
        border-collapse: collapse;


    }

    th {
        background-color: #FFA500;
    }

    .addBtn {
        text-align: center;
        display: inline-block;
        font-size: 16px;
        margin-top: 10px;
        cursor: pointer;
        border-radius: 5px;
    }
</style>




<?php
get_footer();
?>