<?php
class ModelCatalogCustomerSpecials extends Model {

	public function editCustomerSpecials($customer_group_id, $data) {
	      //Змінюємо процент знижки в customer_group для даної групи
        $this->db->query("UPDATE " . DB_PREFIX . "customer_group  SET percent = '" . (int)$data['percent'] .  "', date_start = '" . $data['start_date'] .  "', date_end = '" . $data['end_date'] .  "' 
         WHERE customer_group_id = '" . (int)$customer_group_id . "'");
        //DELETE по customer_group_id в product_special
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE priority = '99999' AND customer_group_id = '" . (int)$customer_group_id . "'");

                $start_date = $data['start_date'];
                $end_date =$data['end_date'];
                $discount = $data['percent'];
            //    $customer_group_id = $data['customer_group_id'];
              //  if ($discount>0){
                $query = $this->db->query("SELECT product_id, price FROM " . DB_PREFIX . "product");
		           foreach ($query->rows as $row) {
                       $product_id = $row['product_id'];
                       $discounted_price = (float) $row['price'] * (100 - (int) $discount) / 100;
                       $this->db->query("INSERT INTO " . DB_PREFIX . "product_special 
                       SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', priority =  '99999',
                        price = '" . $discounted_price . "', date_start =  '" . $start_date. "', date_end =  '" . $end_date . "'");

               //    }


            }

        }

    public function repairCustomerSpecials( )
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE priority = '99999'");
        $qr_custom = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_group WHERE percent<>'0' ");
        $qr_product = $this->db->query("SELECT product_id, price FROM " . DB_PREFIX . "product");
        foreach ($qr_custom->rows as $key) {
            $customer_group_id = $key['customer_group_id'];
            $discount = $key['percent'];
            $start_date = $key['date_start'];
            $end_date = $key['date_end'];
            foreach ($qr_product->rows as $row) {
                $product_id = $row['product_id'];
                $discounted_price = (float)$row['price'] * (100 - (int)$discount) / 100;
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_special 
                       SET  product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', priority =  '99999',
                      date_start =  '" . $start_date . "', date_end =  '" . $end_date . "',  price = '" . $discounted_price . "'  ");


            }

        }
    }



    public function getCustomerGroup($customer_group_id) {
      //  $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer_group_description WHERE customer_group_id = '" . (int)$customer_group_id . "'"." AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
        $sql = "SELECT * FROM " . DB_PREFIX . "customer_group ag LEFT JOIN " . DB_PREFIX . "customer_group_description agd ON (ag.customer_group_id = agd.customer_group_id)
        WHERE ag.customer_group_id = '" . (int)$customer_group_id . "'"." AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
        $query = $this->db->query($sql);
        return $query->row;
    }
    public function getCustomersGroups($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "customer_group ag LEFT JOIN " . DB_PREFIX . "customer_group_description agd ON (ag.customer_group_id = agd.customer_group_id) WHERE agd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalCustomersGroups() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_group");

        return $query->row['total'];
    }
}
