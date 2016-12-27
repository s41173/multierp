<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'inventaris';
    
    function get_last()
    {
        $this->db->select('inventaris.id, inventaris.no, inventaris.currency, inventaris.room, inventaris_category.name as category, inventaris.name, inventaris.desc, inventaris.buying, inventaris.price');
        $this->db->from('inventaris,inventaris_category');
        $this->db->where('inventaris.category_id = inventaris_category.id');
        return $this->db->get(); 
    }
    
    function search($cat=null, $room=null, $name=null, $date=null)
    {
        $this->db->select('inventaris.id, inventaris.no, inventaris.currency, inventaris.room, inventaris_category.name as category, inventaris.name, inventaris.desc, inventaris.buying, inventaris.price');
        $this->db->from('inventaris,inventaris_category');
        $this->db->where('inventaris.category_id = inventaris_category.id');
        $this->cek_null($cat,"inventaris.category_id");
        $this->cek_null($room,"inventaris.room");
        $this->cek_null($name,"inventaris.name");
        $this->cek_null($date,"inventaris.buying");
        return $this->db->get(); 
    }
    
    function report($cat=null, $room=null, $start=null, $end=null)
    {
        $this->db->select('inventaris.id, inventaris.no, inventaris.currency, inventaris.room, inventaris_category.name as category, inventaris.name, inventaris.desc, inventaris.buying, inventaris.price');
        $this->db->from('inventaris,inventaris_category');
        $this->db->where('inventaris.category_id = inventaris_category.id');
        $this->cek_null($cat,"inventaris.category_id");
        $this->cek_null($room,"inventaris.room");
        $this->cek_between($start, $end);
        return $this->db->get(); 
    }
 
    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }
    
    private function cek_between($start,$end)
    {
        if ($start == null || $end == null ){return null;}
        else { return $this->db->where("inventaris.buying BETWEEN '".$start."' AND '".$end."'"); }
    }

}

?>