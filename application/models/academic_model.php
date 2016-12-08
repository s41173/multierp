<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Academic_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->db2 = $this->load->database('second', TRUE);
    }
    
    
    var $dept = 'dept';
    var $faculty = 'faculty';
    var $grade = 'grade';
    private $db2;
    
    function hello()
    {
        return 'hello';
    }
    
    function combo_dept()
    {
        $this->db2->select('dept_id, name');
        $val = $this->db2->get($this->dept)->result();
        foreach($val as $row){$data['options'][$row->dept_id] = $row->name;}
        return $data;
    }
    
    function get_dept($id=0)
    {
       $this->db2->select('name');
       $this->db2->from($this->dept);
       $this->db2->where('dept_id', $id);
       return $this->db2->get()->row(); 
    }
    
    function combo_faculty()
    {
        $this->ci->db2->select('faculty_id, name');
        $val = $this->ci->db2->get($this->faculty)->result();
        foreach($val as $row){$data['options'][$row->faculty_id] = $row->name;}
        return $data;
    }
    
    function get_faculty($id=0)
    {
       $this->db2->select('name');
       $this->db2->from($this->faculty);
       $this->db2->where('faculty_id', $id);
       return $this->db2->get()->row(); 
    }
    
    function combo_grade()
    {
        $this->ci->db2->select('grade_id, name');
        $val = $this->ci->db2->get($this->grade)->result();
        foreach($val as $row){$data['options'][$row->grade_id] = $row->name;}
        return $data;
    }
    
    function get_grade($id=0)
    {
       $this->db2->select('name');
       $this->db2->from($this->grade);
       $this->db2->where('grade_id', $id);
       return $this->db2->get()->row(); 
    }

}

?>