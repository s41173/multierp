<?php
/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2014 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package	PHPExcel_Style
 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license	http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version	1.8.0, 2014-03-02
 */


/**
 * PHPExcel_Style_Font
 *
 * @category   PHPExcel
 * @package	PHPExcel_Style
 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 */
class PHPExcel_Style_Font extends PHPExcel_Style_Supervisor implements PHPExcel_IComparable
{
	/* Underline types */
	const UNDERLINE_NONE					= 'none';
	const UNDERLINE_DOUBLE					= 'double';
	const UNDERLINE_DOUBLEACCOUNTING		= 'doubleAccounting';
	const UNDERLINE_SINGLE					= 'single';
	const UNDERLINE_SINGLEACCOUNTING		= 'singleAccounting';

	/**
	 * Font Name
	 *
	 * @var string
	 */
	protected $_name			= 'Calibri';

	/**
	 * Font Size
	 *
	 * @var float
	 */
	protected $_size			= 11;

	/**
	 * Bold
	 *
	 * @var boolean
	 */
	protected $_bold			= FALSE;

	/**
	 * Italic
	 *
	 * @var boolean
	 */
	protected $_italic		= FALSE;

	/**
	 * Superscript
	 *
	 * @var boolean
	 */
	protected $_superScript	= FALSE;

	/**
	 * Subscript
	 *
	 * @var boolean
	 */
	protected $_subScript		= FALSE;

	/**
	 * Underline
	 *
	 * @var string
	 */
	protected $_underline		= self::UNDERLINE_NONE;

	/**
	 * Strikethrough
	 *
	 * @var boolean
	 */
	protected $_strikethrough	= FALSE;

	/**
	 * Foreground color
	 *
	 * @var PHPExcel_Style_Color
	 */
	protected $_color;

	/**
	 * Create a new PHPExcel_Style_Font
	 *
	 * @param	boolean	$isSupervisor	Flag indicating if this is a supervisor or not
	 *									Leave this value at default unless you understand exactly what
	 *										its ramifications are
	 * @param	boolean	$isConditional	Flag indicating if this is a conditional style or not
	 *									Leave this value at default unless you understand exactly what
	 *										its ramifications are
	 */
	public function __construct($isSupervisor = FALSE, $isConditional = FALSE)
	{
		// Supervisor?
		parent::__construct($isSupervisor);

		// Initialise values
		if ($isConditional) {
			$this->_name			= NULL;
			$this->_size			= NULL;
			$this->_bold			= NULL;
			$this->_italic			= NULL;
			$this->_superScript		= NULL;
			$this->_subScript		= NULL;
			$this->_underline		= NULL;
			$this->_strikethrough	= NULL;
			$this->_color			= new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_BLACK, $isSupervisor, $isConditional);
		} else {
			$this->_color	= new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_BLACK, $isSupervisor);
		}
		// bind parent if we are a supervisor
		if ($isSupervisor) {
			$this->_color->bindParent($this, '_color');
		}
	}

	/**
	 * Get the shared style component for the currently active cell in currently active sheet.
	 * Only used for style supervisor
	 *
	 * @return PHPExcel_Style_Font
	 */
	public function getSharedComponent()
	{
		return $this->_parent->getSharedComponent()->getFont();
	}

	/**
	 * Build style array from subcomponents
	 *
	 * @param array $array
	 * @return array
	 */
	public function getStyleArray($array)
	{
		return array('font' => $array);
	}

	/**
	 * Apply styles from array
	 *
	 * <code>
	 * $objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->applyFromArray(
	 *		array(
	 *			'name'		=> 'Arial',
	 *			'bold'		=> TRUE,
	 *			'italic'	=> FALSE,
	 *			'underline' => PHPExcel_Style_Font::UNDERLINE_DOUBLE,
	 *			'strike'	=> FALSE,
	 *			'color'		=> array(
	 *				'rgb' => '808080'
	 *			)
	 *		)
	 * );
	 * </code>
	 *
	 * @param	array	$pStyles	Array containing style information
	 * @throws	PHPExcel_Exception
	 * @return PHPExcel_Style_Font
	 */
	public function applyFromArray($pStyles = null) {
		if (is_array($pStyles)) {
			if ($this->_isSupervisor) {
				$this->getActiveSheet()->getStyle($this->getSelectedCells())->applyFromArray($this->getStyleArray($pStyles));
			} else {
				if (array_key_exists('name', $pStyles)) {
					$this->setName($pStyles['name']);
				}
				if (array_key_exists('bold', $pStyles)) {
					$this->setBold($pStyles['bold']);
				}
				if (array_key_exists('italic', $pStyles)) {
					$this->setItalic($pStyles['italic']);
				}
				if (array_key_exists('superScript', $pStyles)) {
					$this->setSuperScript($pStyles['superScript']);
				}
				if (array_key_exists('subScript', $pStyles)) {
					$this->setSubScript($pStyles['subScript']);
				}
				if (array_key_exists('underline', $pStyles)) {
					$this->setUnderline($pStyles['underline']);
				}
				if (array_key_exists('strike', $pStyles)) {
					$this->setStrikethrough($pStyles['strike']);
				}
				if (array_key_exists('color', $pStyles)) {
					$this->getColor()->applyFromArray($pStyles['color']);
				}
				if (array_key_exists('size', $pStyles)) {
					$this->setSize($pStyles['size']);
				}
			}
		} else {
			throw new PHPExcel_Exception("Invalid style array passed.");
		}
		return $this;
	}

	/**
	 * Get Name
	 *
	 * @return string
	 */
	public function getName() {
		if ($this->_isSupervisor) {
			return $this->getSharedComponent()->getName();
		}
		return $this->_name;
	}

	/**
	 * Set Name
	 *
	 * @param string $pValue
	 * @return PHPExcel_Style_Font
	 */
	public function setName($pValue = 'Calibri') {
  		if ($pValue == '') {
			$pValue = 'Calibri';
		}
		if ($this->_isSupervisor) {
			$styleArray = $this->getStyleArray(array('name' => $pValue));
			$this->getActiveSheet()->getStyle($this->getSelectedCells())->applyFromArray($styleArray);
		} else {
			$this->_name = $pValue;
		}
		return $this;
	}

	/**
	 * Get Size
	 *
	 * @return double
	 */
	public function getSize() {
		if ($this->_isSupervisor) {
			return $this->getSharedComponent()->getSize();
		}
		return $this->_size;
	}

	/**
	 * Set Size
	 *
	 * @param double $pValue
	 * @return PHPExcel_Style_Font
	 */
	public function setSize($pValue = 10) {
		if ($pValue == '') {
			$pValue = 10;
		}
		if ($this->_isSupervisor) {
			$styleArray = $this->getStyleArray(array('size' => $pValue));
			$this->getActiveSheet()->getStyle($this->getSelectedCells())->applyFromArray($styleArray);
		} else {
			$this->_size = $pValue;
		}
		return $this;
	}

	/**
	 * Get Bold
	 *
	 * @return boolean
	 */
	public function getBold() {
		if ($this->_isSupervisor) {
			return $this->getSharedComponent()->getBold();
		}
		return $this->_bold;
	}

	/**
	 * Set Bold
	 *
	 * @param boolean $pValue
	 * @return PHPExcel_Style_Font
	 */
	public function setBold($pValue = false) {
		if ($pValue == '') {
			$pValue = false;
		}
		if ($this->_isSupervisor) {
			$styleArray = $this->getStyleArray(array('bold' => $pValue));
			$this->getActiveSheet()->getStyle($this->getSelectedCells())->applyFromArray($styleArray);
		} else {
			$this->_bold = $pValue;
		}
		return $this;
	}

	/**
	 * Get Italic
	 *
	 * @return boolean
	 */
	public function getItalic() {
		if ($this->_isSupervisor) {
			return $this->getSharedComponent()->getItalic();
		}
		return $this->_italic;
	}

	/**
	 * Set Italic
	 *
	 * @param boolean $pValue
	 * @return PHPExcel_Style_Font
	 */
	public function setItalic($pValue = false) {
		if ($pValue == '') {
			$pValue = false;
		}
		if ($this->_isSupervisor) {
			$styleArray = $this->getStyleArray(array('italic' => $pValue));
			$this->getActiveSheet()->getStyle($this->getSelectedCells())->applyFromArray($styleArray);
		} else {
			$this->_italic = $pValue;
		}
		return $this;
	}

	/**
	 * Get SuperScript
	 *
	 * @return boolean
	 */
	public function getSuperScript() {
		if ($this->_isSupervisor) {
			return $this->getSharedComponent()->getSuperScript();
		}
		return $this->_superScript;
	}

	/**
	 * Set SuperScript
	 *
	 * @param boolean $pValue
	 * @return PHPExcel_Style_Font
	 */
	public function setSuperScript($pValue = false) {
		if ($pValue == '') {
			$pValue 