<?php 

class ModelExtensionApiHomeHome extends Model {
	public function getHomePageLayout($layout_id){
            $query = $this->db->query("SELECT layout_name FROM " . DB_PREFIX . "storeapp_layouts WHERE id_layout = '" . (int) $layout_id . "'");
            if ($query->num_rows) {
                return $query->row['layout_name'];
            }else{
                return false;
            }
        }
        
        public function getHomePageLayoutSort($layout_id){
            $query = $this->db->query("SELECT layout_sort FROM " . DB_PREFIX . "storeapp_layouts WHERE id_layout = '" . (int) $layout_id . "'");
            if ($query->num_rows) {
                return $query->row['layout_sort'];
            }else{
                return false;
            }
        }
        


}
?>