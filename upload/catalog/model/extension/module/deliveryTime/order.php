<?php
class ModelExtensionModuleDeliveryTimeOrder extends Model {
     
        
        //   Delivery Time 
        
        public function addDeliveryTime($order_id,$deliveryTime){

        	//var_dump($this->session->data['delivery_time']);
            
            
            $times = explode(" - ",$this->session->data['delivery_time']);
            
            $tm1=$times[0];
           // $tm1 =  date_format($date,"y-m-d H:i:s");
            $tm2=$times[1];
           // $tm2 =  date_format($date1,"y-m-d H:i:s");
            $new_time = $tm1." - ".$tm2;
          $deliveryTimegordian=$this->session->data['delivery_shamci_time'];
            $this->db->query("INSERT INTO " . DB_PREFIX . "delivery_time " . "SET order_id = '" . (int)$order_id . "', delivery_time = '" . $this->db->escape($deliveryTimegordian) . "', times='".$new_time."'");
            
            
        }
        
        // Get delivert Time
        public function getDeliveryTime($order_id){
            
          $order_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "delivery_time WHERE order_id = '" . (int)$order_id . "'");  
          
          foreach ($order_query->rows as $order_time)
              {
					
              $data = $order_time['times'];
              
              }
          
              return $data;  
        }
        
}