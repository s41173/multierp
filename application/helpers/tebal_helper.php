<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Formats a numbers as bytes, based on size, and adds the appropriate suffix
 *
 * @access	public
 * @param	mixed	// will be cast as int
 * @return	string
 */
if ( ! function_exists('cetak_tebal'))
{
	function cetak_tebal($val)
	{
            return "<b>".$val."</b>";
	}	
}

/* End of file number_helper.php */
/* Location: ./system/helpers/number_helper.php */