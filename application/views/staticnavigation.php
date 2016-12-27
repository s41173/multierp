<div class='staticmenu'>

<ul>
	   <li class="isFolder"><a href="#">Menu</a>
            <ul>
				<li><?php echo anchor('adminmenu', 'Admin Menu');?></li>
            </ul>
       </li>

	    <li class="isFolder"> <a href="#">Configuration</a>
            <ul>
			   <li><?php echo anchor('admin', 'Web Admin');?></li>
			   <li><?php echo anchor('component', 'Component Manager');?></li>
			   <li><?php echo anchor('log', 'History');?></li>
			   <li><?php echo anchor('roles', 'Role');?></li>
			   <li><?php echo anchor('configuration', 'Global Configuration');?></li>
               
               <li class="isFolder"><?php echo anchor('closing', 'Period End');?>
               <ul>  
               	  <li><?php echo anchor('closing/monthly', 'Month End');?></li>
                  <li><?php echo anchor('closing/annual', 'Year End');?></li>
               </ul>
               </li>
               
               <li><?php echo anchor('foundation', 'Foundation Staff');?></li>
			   <li><?php echo anchor('installation', 'Installation');?></li>
            </ul>
        </li>
</ul>

</div>

<script>
    $('.staticmenu').easytree();
</script>