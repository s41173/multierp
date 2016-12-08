<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log {

    public function __construct($params=null)
    {
        // Do something with $params
        $this->ci = & get_instance();
    }

    private $table = 'log';
    private $ci;

    public function max_log()
    {
        $this->ci->db->select_max('logid');
        $val = $this->ci->db->get($this->table)->row_array();
        $val = $val['logid'];
        return $val;
    }

    public function insert($userid=null, $date=null, $time=null, $activity=null)
    {
        $logs = array('userid' => $userid, 'date' => $date, 'time' => $time, 'activity' => $activity);
        $this->ci->db->insert($this->table, $logs);
    }
}

/* End of file Property.php */