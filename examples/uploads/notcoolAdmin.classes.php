<?php
 class Admin extends Dbh {
     public $email;
     public $admin_name;
     public function __construct($admin_id){
         $admin_details = $this->check_account($admin_id);
         switch ($admin_details) {
             case 'true':
             case 'false':
             case 'stmt Error':
                header('location:../inc/logout.inc.php');
                exit;
                 # code...
                 break;
             
             default:
             $admin_details = $admin_details[0];
             $this->email = $admin_details['email'];
             $this->admin_name = $admin_details['admin_name'];
                 # code...
                 break;
         }

     }
     public function add_product($product){
         $image0 = $product[8][0];
         $image1 = $product[8][1];
         $image2 = $product[8][2];
         $image3 = $product[8][3];
         $image4 = $product[8][4];
        $sql = "INSERT INTO products (product_id, product_name,  product_size , product_category,product_price,  discount_method, product_discount, product_quantity, product_image1, product_image2, product_image3, product_image4, product_image5,  product_perm_link,   addedby, product_gender) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?, ?);";

        $stmt = $this->connect()->prepare($sql);
        $stmt_status = $stmt->execute([$product[0], $product[1], $product[2], $product[3], $product[4], $product[5], $product[6], $product[7], $image0, $image1, $image2, $image3, $image4, $product[9], $this->email, $product[10]]);

        if(!$stmt_status){
            return false;
        }
        else{
            return true;
        }

     }

     public function delete_product($product_id){
         $sql = "UPDATE products set active_status = ? where product_id = ?;";
         $stmt= $this->connect()->prepare($sql);
         $stmt_status = $stmt->execute(['deleted', $product_id]);
         if(!$stmt_status){
             return false;
         }
         else{
             return true;
         }
     }

     public function get_products($product_id){
         $sql = "SELECT * FROM products WHERE product_id = ? LIMIT 1";
         $stmt = $this->connect()->prepare($sql);
         $stmt_status = $stmt->execute([$product_id]);
         if(!$stmt_status){
             return false;

         }
         elseif($stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
         }
         else{
             return false;
         }

     }

     

    public function editProduct(Array $edit_array,  $product_id){
        if($this->get_products($product_id) == false){
            return false;
        }
        
        $total_delivery = $this->get_products($product_id)[0]['total_delivery'];
        $new_quantity_left = $edit_array['edit_quantity'];
        $new_quantity_added = $new_quantity_left - $total_delivery;
        $edit_array['edit_quantity'] = $new_quantity_added;
        
        // var_dump($edit_array);
        // echo "<br>".__LINE__ ;
        // echo "<br>".__LINE__ ;
        // echo "<br>";
        // var_dump(rsort($edit_array));
        // $numbers = array(4, 6, 2, 22, 11);
        // echo sort($numbers);
        


        $sql = "UPDATE `products`
                SET `product_name` = ?,
                    `product_size` = ?,
                    `product_gender` = ?,
                    `product_category` = ?,
                    `product_price` = ?,
                    `product_discount` = ?,
                    `discount_method` = ?,
                    `product_quantity` = ?
              WHERE `product_id` = ? ;";
    
        $stmt= $this->attachDb()->prepare($sql);
         $stmt_status = $stmt->execute([$edit_array['edit_name'],
                                        $edit_array['edit_size'],
                                        $edit_array['edit_gender'],
                                        $edit_array['edit_category'],
                                        $edit_array['edit_price'],
                                        $edit_array['edit_discount'],
                                        $edit_array['edit_discount_method'],
                                        $edit_array['edit_quantity'],
                                        $product_id
                                        ]);
         if(!$stmt_status){
             return false;
         }
         else{
             return true;
         }

    }
 }