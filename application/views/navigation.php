   <?php
      function get_menu($data, $parent = 0)
      {
	      static $i = 1;
	      $tab = str_repeat(" ", $i);
	      if (isset($data[$parent])) {
		      $html = "$tab<ul class='' id=''>";
		      $i++;
		      foreach ($data[$parent] as $v) {
			       $child = get_menu($data, $v->id);
			       $html .= "$tab<li class='isFolder'>";
			       $html .= anchor($v->url, $v->name);
			       if ($child) {
				       $i--;
				       $html .= $child;
				       $html .= "$tab";
			       }
			       $html .= "</li>";
		      }
		      $html .= "$tab</ul>";
		      return $html;
	      }
              else {return false; }
      }


      $result = mysql_query("SELECT * FROM admin_menu ORDER BY menu_order");
      while ($row = mysql_fetch_object($result)) {
	       $data[$row->parent_id][] = $row;
      }

     // $menu = get_menu($data);
     // echo "$menu";
    ?>
    
	<div class="treemenu">
    	
    <?php  
	  $menu = get_menu($data);
      echo "$menu";
	?>
    
	</div>
    
    <script>
	    $('.treemenu').easytree();
    </script>