<?php
session_start();
//update the ratings, buyer comment and seller comment in archive table after sale. This script grabs the data submitted by form from
//purchasehistory.php (for buyer) or sellerhistory.php (for seller)

if (isset($_SESSION["aftersale_seller"])||(isset($_SESSION["aftersale_buyer"]))) {

    include "../database.php";

    if(isset($_SESSION["aftersale_seller"])){

            //update seller comments

            $archiveID=mysqli_real_escape_string($connection,$_SESSION["aftersale_seller"][0]);
            $sellercomment=mysqli_real_escape_string($connection,$_SESSION["aftersale_seller"][1]);
            
            $sql="UPDATE Archive
                    SET seller_comment='$sellercomment'
                    WHERE archiveID=$archiveID";

    }elseif(isset($_SESSION["aftersale_buyer"])){

        //update ratings and buyer comments

        $archiveID=mysqli_real_escape_string($connection,$_SESSION["aftersale_buyer"][0]);
        $buyercomment=mysqli_real_escape_string($connection,$_SESSION["aftersale_buyer"][1]);
        $ratings=mysqli_real_escape_string($connection,$_SESSION["aftersale_buyer"][2]);
        
        $sql="UPDATE Archive
                SET buyer_comment='$buyercomment',
                    ratings=$ratings
                WHERE archiveID=$archiveID";

    }


    $result=$connection->query($sql);
    
    if ($connection->query($sql)==TRUE){
    echo "successfully updated";

    } else {
    echo "Error: ". $sql . "<br>" . $connection->error;
    }
  
    $connection->close();

    unset($_SESSION["aftersale_buyer"]);
    unset($_SESSION["aftersale_seller"]);

}
?>
