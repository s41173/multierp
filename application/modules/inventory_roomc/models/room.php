<?php

class Room extends DataMapper
{
   var $table = "inventaris_room";
//   var $has_one = array("account");
//   var $has_many = array("category");
   var $auto_populate_has_many = TRUE;
   var $auto_populate_has_one = TRUE;

   function __construct($id = NULL)
   {
      parent::__construct($id);
   }

}

/* End of file user.php */
/* Location: ./application/models/user.php */