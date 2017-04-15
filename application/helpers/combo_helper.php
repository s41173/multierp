<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Formats a numbers as bytes, based on size, and adds the appropriate suffix
 *
 * @access	public
 * @param	mixed	// will be cast as int
 * @return	string
 */


if (! function_exists('lang'))
{
    function lang()
    {
        $CI =& get_instance();
        $query_employee = $CI->Language_model->get_language_name();
	$employeex = $query_employee->result();
	$num_rows = $query_employee->num_rows();
	if ($num_rows > 0)
	{foreach($employeex as $row){$data['options_lang'][$row->code] = $row->code;}}
	else{$data['options_lang'][''] = '';}
        return $data;
    }
}

if (! function_exists('setnull'))
{
    function setnull($value)
    {if ($value == ""){$value = null;}return $value;}
}

if (! function_exists('replace'))
{
    function replace($replace,$replacewith,$inme)
    {
        $doit = str_replace ("$replace", "$replacewith", $inme);
        return strtolower("$doit");
    }
}

if (! function_exists('num_format'))
{
    function num_format($val)
    {
       return number_format($val,2,',','.');
    }
}





/* End of file combo_helper.php */
/* Location: ./system/helpers/combo_helper.php */