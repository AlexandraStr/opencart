<?php
class ModelToolImport extends Model {
	public function getManufacturer($sbrend){
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "`manufacturer` WHERE name = '" . $this->db->escape($sbrend) . "'");
        return $query->rows;
        }
    public function getCategory($categ) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "`category_description` WHERE name = '" . $this->db->escape($categ)."'" );
        return $query->rows;
    }
    public function getCategories() {
        $query = $this->db->query("SELECT category_id,import_id FROM " . DB_PREFIX . "`category` WHERE import_id <> 0 ORDER BY import_id " );
        return $query->rows;
    }
    public function getProduct($model) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "`product` WHERE model = '" . $this->db->escape($model)."'");
        return $query->rows;
    }
    public function showOrders(){
	    $table_data = array();
        $query = $this->db->query("SELECT order_id,invoice_prefix,firstname,lastname,payment_company,date_added FROM " . DB_PREFIX . " `order`  ORDER BY date_added");
        foreach ($query->rows as $result) {
            $table_dates[]= $result;
        }
        return $table_dates;
    }
    public function trancateProduct(){
       $this->db->query("TRUNCATE TABLE ". DB_PREFIX . "`product_to_layout`");
        $this->db->query("TRUNCATE TABLE ". DB_PREFIX . "`product_to_store`");
        $this->db->query("TRUNCATE TABLE ". DB_PREFIX . "`product`");
        $this->db->query("TRUNCATE TABLE ". DB_PREFIX . "`product_to_category`");
        $this->db->query("TRUNCATE TABLE ". DB_PREFIX . "`product_description`");
        $this->db->query("TRUNCATE TABLE ". DB_PREFIX . "`product_special`");
    }
	public function exportOrders($tables){
        $output = '';
        $in_str=implode(",", $tables);
        $sql = "SELECT order_id,product_id,model,name,quantity,price FROM `order_product` where order_id IN (  $in_str ) ORDER BY order_id";
        $query = $this->db->query($sql);
        foreach ($query->rows as $result) {
            $output .= $result['order_id']. ';' .$result['product_id']. ';' . $result['model']. ';' .$result['name']. ';' .$result['price']. ';' .$result['quantity']."\n";
        }
        return $output;
    }

    public function exportOrdersForDate(){
        $output = '';
        $query = $this->db->query("SELECT order_id FROM `order` where date(date_added) = CURDATE()");
        $tables = array();
        foreach ($query->rows as $row){
            $tables[]=$row['order_id'];
        }
       if ($tables) {
            $in_str = implode(",", $tables);

            $sql = "SELECT order_id,product_id,model,name,quantity,price FROM `order_product` where order_id IN (  $in_str ) ORDER BY order_id";
            $query = $this->db->query($sql);
            foreach ($query->rows as $result) {
                $output .= $result['order_id'] . ';' . $result['product_id'] . ';' . $result['model'] . ';' . $result['name'] . ';' . $result['price'] . ';' . $result['quantity'] . "\n";
            }
            return $output;
        }
    }

    
    
    public function UpdateCsv($setstr,$wherestr){
        $this->db->query("UPDATE ". DB_PREFIX ."`product` SET ".$setstr." WHERE model='".$wherestr."'");

    }
}
