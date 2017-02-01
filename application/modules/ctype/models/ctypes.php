<?php

class Ctypes extends DataMapper
{
//   var $has_one = array("country");
   var $table = "contract_type";
   var $auto_populate_has_one = TRUE;

   function __construct($id = NULL)
   {
      parent::__construct($id);
   }

}

/* End of file user.php */
/* Location: ./application/models/user.php */