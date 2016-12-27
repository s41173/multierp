<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');


function editor()
{
    //Ckeditor's configuration
    $data = array(

            //ID of the textarea that will be replaced
            'id' 	=> 	'content',
            'path'	=>	'js/ckeditor',

            //Optionnal values
            'config' => array(
            'toolbar' 	=> 	"Full", 	//Using the Full toolbar
            'width' 	=> 	"815px",	//Setting a custom width
            'height' 	=> 	'450px',	//Setting a custom height
            ),

            //Replacing styles from the "Styles tool"
            'styles' => array(
            //Creating a new style named "style 1"
            'style 1' => array ('name' => 'Blue Title', 'element' => 'h2',
            'styles' => array('color' => 'Blue', 'font-weight' => 'bold' )),

            //Creating a new style named "style 2"
            'style 2' => array ('name' 	=> 'Red Title', 'element' 	=> 'h2',
            'styles' => array('color' => 'Red', 'font-weight' => 'bold', 'text-decoration' => 'underline')))
	);

    return $data;
}