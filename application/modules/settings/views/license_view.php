<style type="text/css">
	.descp{ padding:10px 0 5px 10px; margin:0; font-size:24px; font-family: Verdana, Arial, Helvetica, sans-serif; color:#025A9F; font-weight:bold;}
	
			#tombol{ border:1px solid #AAAAAA; padding:2px 2px 2px 2px; margin:0px 2px 0px 2px;}
		#tombol:hover{ background-color:#CCCCCC; color:#000099;}
		
	    .refresh{ border:1px solid #AAAAAA; color:#000; padding:2px 5px 2px 5px; margin:0px 2px 0px 2px; background-color:#FFF;}
		.refresh:hover{ background-color:#CCCCCC; color: #FF0000;}
		.refresh:visited{ background-color:#FFF; color: #000000;}

</style>
	


<div id="webadmin">
    <p class="descp"> License </p>
	<p class="message"> <?php echo ! empty($message) ? $message : '' . ! empty($flashmessage) ? $flashmessage : ''; ?> </p>
	
	<p style="color:#0000FF; font-size:14px; padding:10px 5px 10px 10px;"> GNU GENERAL PUBLIC LICENSE </p>
	
	<p style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; padding:10px 10px 10px 10px;"> 
	Version 2, June 1991
    Copyright (C) 1989, 1991 Free Software Foundation, Inc.
    59 Temple Place - Suite 330, Boston, MA  02111-1307, USA

    Everyone is permitted to copy and distribute verbatim copies
    of this license document, but changing it is not allowed. <br />
    </p>
	
	<p style="font-family: Verdana; font-size:11px; padding:10px 10px 10px 10px; text-align:justify;">
	The licenses for most software are designed to take away your freedom to share and change it. By contrast, the GNU General Public License is intended to guarantee your freedom to share and change free software--to make sure the software is free for all its users. This General Public License applies to most of the Free Software Foundation's software and to any other program whose authors commit to using it. (Some other Free Software Foundation software is covered by the GNU Library General Public License instead.) You can apply it to your programs, too.

When we speak of free software, we are referring to freedom, not price. Our General Public Licenses are designed to make sure that you have the freedom to distribute copies of free software (and charge for this service if you wish), that you receive source code or can get it if you want it, that you can change the software or use pieces of it in new free programs; and that you know you can do these things.

To protect your rights, we need to make restrictions that forbid anyone to deny you these rights or to ask you to surrender the rights. These restrictions translate to certain responsibilities for you if you distribute copies of the software, or if you modify it.

For example, if you distribute copies of such a program, whether gratis or for a fee, you must give the recipients all the rights that you have. You must make sure that they, too, receive or can get the source code. And you must show them these terms so they know their rights.

We protect your rights with two steps: (1) copyright the software, and (2) offer you this license which gives you legal permission to copy, distribute and/or modify the software.

Also, for each author's protection and ours, we want to make certain that everyone understands that there is no warranty for this free software. If the software is modified by someone else and passed on, we want its recipients to know that what they have is not the original, so that any problems introduced by others will not reflect on the original authors' reputations.

Finally, any free program is threatened constantly by software patents. We wish to avoid the danger that redistributors of a free program will individually obtain patent licenses, in effect making the program proprietary. To prevent this, we have made it clear that any patent must be licensed for everyone's free use or not licensed at all.

The precise terms and conditions for copying, distribution and modification follow. 
	
	</p>
	
	<p style="color:#0000FF; font-size:14px; padding:10px 5px 5px 10px;"> How to Apply These Terms to Your New Programs </p>
	<p style="font-family: Verdana; font-size:11px; padding:10px 10px 10px 10px; text-align:justify;">
	
		How to Apply These Terms to Your New Programs

If you develop a new program, and you want it to be of the greatest possible use to the public, the best way to achieve this is to make it free software which everyone can redistribute and change under these terms.

To do so, attach the following notices to the program. It is safest to attach them to the start of each source file to most effectively convey the exclusion of warranty; and each file should have at least the "copyright" line and a pointer to where the full notice is found.

one line to give the program's name and an idea of what it does.
Copyright (C) yyyy  name of author

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA
02111-1307, USA.

Also add information on how to contact you by electronic and paper mail.

If the program is interactive, make it output a short notice like this when it starts in an interactive mode:

Gnomovision version 69, Copyright (C) year name of author
Gnomovision comes with ABSOLUTELY NO WARRANTY; for details
type `show w'.  This is free software, and you are welcome
to redistribute it under certain conditions; type `show c'
for details.

	
	</p>
	
</div>


<?php  
	
		$atts = array(
			  'class'      => 'refresh',
			  'title'      => 'Checkout Invoice',
			  'width'      => '650',
			  'height'     => '300',
			  'scrollbars' => 'no',
			  'status'     => 'yes',
			  'resizable'  => 'yes',
			  'screenx'    =>  '\'+((parseInt(screen.width) - 650)/2)+\'',
			  'screeny'    =>  '\'+((parseInt(screen.height) - 300)/2)+\'',
		);
	
	?>

<div id="webadmin2">
	
	<table align="right" style="margin:10px 0px 0 0; padding:3px; " width="100%" bgcolor="#D9EBF5">
	<tbody>
		<tr> 
		   <td align="right"> 
		   		<?php echo anchor(site_url("settings/property"), 'PROPERTY CONFIGURATION', $atts); ?>
				<?php //echo anchor(site_url("installation/remove"), 'REMOVE CONFIGURATION', $atts); ?>
<!--				<button class="" onclick=""> SAVE &amp; BACKUP </button>
				<button class="" onclick="myFunction()"> REMOVE CONFIGURATION </button> -->
		   </td> 
		</tr>
	</tbody>
	</table>
	
	<!-- links -->
	<div class="buttonplace"> <?php if (!empty($link)){foreach($link as $links){echo $links . '';}} ?> </div>
</div>



<!-- batas -->

