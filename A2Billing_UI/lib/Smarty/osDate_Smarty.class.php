<?php

/* Smarty class is extended for use with osdate.
Vijay Nair      25 May 2006    */

class osDate_Smarty extends Smarty{

	/**
	 * assigns values to template variables
	 *
	 * @param array|string $tpl_var the template variable name(s)
	 * @param mixed $value the value to assign
	 */
	/**
	 * assigns values to template variables
	 *
	 * @param array|string $tpl_var the template variable name(s)
	 * @param mixed $value the value to assign
	 */
	function assign($tpl_var, $value = null)
	{
		if (is_array($tpl_var)){
			foreach ($tpl_var as $key => $val) {
				if ($key != '') {

					if ( !is_array( $value ) ) {
						$this->_tpl_vars[$key] = stripslashes( $val );
					}
					else {
						foreach( $val as $index => $v ) {
							if ( !is_array( $v ) )
								$val[ $index ] = stripslashes( $v );
							else
								$val[ $index ] = $v;
						}

						$this->_tpl_vars[$key] = $val;
					}
				}
			}
		} else {
			if ($tpl_var != '') {

				if ( !is_array( $value ) ) {
					$this->_tpl_vars[$tpl_var] = stripslashes( $value );
				}
				else {
					foreach( $value as $index => $v ) {
						if ( !is_array( $v ) )
							$value[ $index ] = stripslashes( $v );
						else
							$value[ $index ] = $v;
					}

					$this->_tpl_vars[$tpl_var] = $value;
				}
			}
		}
	}



}