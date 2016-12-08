<?php

class Tuition_over_transs extends DataMapper
{
//   var $has_one = array("country");
   var $table = "tuition_over_trans";
//   var $has_one = array("vendor");
   var $auto_populate_has_many = TRUE;
   var $auto_populate_has_one = TRUE;

   function __construct($id = NULL)
   {
      parent::__construct($id);
   }

}

/* End of file user.php */
/* Location: ./application/models/user.php */