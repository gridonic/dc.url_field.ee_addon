<?php
/*
=====================================================
URL Field
-----------------------------------------------------
http://www.designchuchi.ch/
-----------------------------------------------------
Copyright (c) 2008 - today Designchuchi
=====================================================
THIS MODULE IS PROVIDED "AS IS" WITHOUT WARRANTY OF
ANY KIND OR NATURE, EITHER EXPRESSED OR IMPLIED,
INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE,
OR NON-INFRINGEMENT.

IN ADDITION TO THE EE DOCUMENTATION, INSPIRED BY THE
WONDERFULLY CODED MODULES
FROM MARK HUOT http://docs.markhuot.com
AND
LEEVI GRAHAM http://leevigraham.com.

DEFAULT ICON FROM FAMFAM
http://www.famfamfam.com/lab/icons/silk/
=====================================================
File: ext.dc_url_field.php
-----------------------------------------------------
Purpose: Adds custom URL Field to EE
=====================================================
*/

if (!defined('EXT'))
{
	exit('Invalid file request');
}

class DC_URL_Field
{

	var $settings		= array();

	var $name			= 'URL Field Extension';
	var $version		= '1.0.3';
	var $description	= 'Adds a URL field providing various checks.';
	var $settings_exist	= 'y';
	var $docs_url		= 'http://www.designchuchi.ch/index.php/blog/comments/url-field-extension-for-expressionengine';

	var $type			= 'url_field';
	var $type_human		= 'URL Field';

	// -------------------------------
	//   Constructor - Extensions use this for settings
	// -------------------------------
	function DC_URL_Field($settings='')
	{
		$this->settings = $settings;
	}

	/**
	 *
	 * Configuration for the extension settings page
	 *
	 * @return  array The settings array
	 */
	function settings()
	{
		$settings = array();

		$settings['field_width']	= '';
		$settings['field_icon']		= '';

		return $settings;
	}

	// --------------------------------
	//  Activate Extension
	// --------------------------------

	function activate_extension()
	{
		global $DB;

		// default setting values
		$default_width = '300px';
		$default_icon  = '/images/icons/link_go.png';

		// hooks array (Thanks to Leevi Graham for this nice idea)
		$hooks = array(
			'show_full_control_panel_end'			=> 'edit_field_groups',
			'publish_admin_edit_field_extra_row'	=> 'add_url_field',
			'publish_form_field_unique'				=> 'display_url_field',
		);

		// default settings
		$default_settings = serialize(
			array(
				'field_width'	=> $default_width,
				'field_icon'	=> $default_icon
			)
		);

		foreach ($hooks as $hook => $method)
		{
			$sql[] = $DB->insert_string( 'exp_extensions',
				array(
					'extension_id'	=> '',
					'class'			=> get_class($this),
					'method'		=> $method,
					'hook'			=> $hook,
					'settings'		=> $default_settings,
					'priority'		=> 10,
					'version'		=> $this->version,
					'enabled'		=> 'y'
				)
			);
		}

		// run all sql queries
		foreach ($sql as $query)
		{
			$DB->query($query);
		}

		return TRUE;
	}

	// --------------------------------
	//  Update Extension
	// --------------------------------
	function update_extension($current = '')
	{
		global $DB;

		if ($current == '' OR $current == $this->version)
		{
			return FALSE;
		}

		$sql[] = "UPDATE exp_extensions SET version = '" . $DB->escape_str($this->version) . "' WHERE class = '" . get_class($this) . "'";

		// run all sql queries
		foreach ($sql as $query)
		{
			$DB->query($query);
		}
	}

	// --------------------------------
	//  Disable Extension
	// --------------------------------
	function disable_extension()
	{
		global $DB;
		$DB->query("DELETE FROM exp_extensions WHERE class = '".get_class($this)."'");
	}

	/**
	 * Modifies control panel html by adding the URL field values to
	 * the Admin > Field Groups table.
	 *
	 * @param  string $out The control panel html
	 * @return string The modified control panel html
	 * @since  Version 1.0.0
	 */
	function edit_field_groups($out)
	{
		global $DB, $EXT, $IN, $REGX;

		// Check if we're not the only one using this hook
		if ($EXT->last_call !== FALSE)
		{
			$out = $EXT->last_call;
		}

		// edit_field_groups() method uses a hook that is called on every CP page load,
		// so it should only execute code if one is on the Field Group manager page of the control panel.
		// After setting $out based on $EXT->last_call

		if ($IN->GBL('M', 'GET') != 'blog_admin' OR ($IN->GBL('P', 'GET') != 'field_editor' && $IN->GBL('P', 'GET') != 'update_weblog_fields'))
		{
			return $out;
		}

		// get the table rows
		if (preg_match_all("/C=admin&amp;M=blog_admin&amp;P=edit_field&amp;field_id=(\d*).*?<\/td>.*?<td.*?>.*?<\/td>.*?<\/td>/is", $out, $matches))
		{

			// get all fields
			$query = $DB->query("SELECT field_id, field_type FROM exp_weblog_fields WHERE field_id IN (" . $DB->escape_str(implode(',', $matches[1])) . ")");

			// for each field
			foreach ($query->result as $field)
			{
				// if the field type is url_field
				if ($field['field_type'] == $this->type)
				{
					$out = preg_replace("/(C=admin&amp;M=blog_admin&amp;P=edit_field&amp;field_id=" . $field['field_id'] . ".*?<\/td>.*?<td.*?>.*?<\/td>.*?)<\/td>/is", "$1" . $REGX->form_prep($this->type_human) . "</td>", $out);
				}
			}
		}

		return $out;
	}

	/**
	 * Adds the URL field to the custom field settings page.
	 * Admin > Field Groups > Custom Fields
	 *
	 * @param  string $data The data about this field from the database
	 * @param  string $r The current contents of the page
	 * @return string The modified control panel html
	 * @since  Version 1.0.0
	 */
	function add_url_field($data, $r) {

		global $EXT, $LANG, $REGX;

		// -- Check if we're not the only one using this hook
		if ($EXT->last_call !== FALSE)
		{
			$r = $EXT->last_call;
		}

		// Add the <option />
		$selected = ($data["field_type"] == $this->type) ? " selected='selected'" : "";

		$r = preg_replace("/(<select.*?name=.field_type.*?value=.select.*?[\r\n])/is", "$1<option value='" . $REGX->form_prep($this->type) . "'" . $selected . ">" . $REGX->form_prep($this->type_human) . "</option>\n", $r);

		// Set which blocks are displayed
		$items = array(
			"date_block" => "none",
			"select_block" => "none",
			"pre_populate" => "none",
			"text_block" => "none",
			"textarea_block" => "none",
			"rel_block" => "none",
			"relationship_type" => "none",
			"formatting_block" => "none",
			"formatting_unavailable" => "block",
			"direction_available" => "none",
			"direction_unavailable" => "block"
		);

		$js = "$1\n\t\telse if (id == '".$this->type."'){";

		foreach ($items as $key => $value)
		{
			$js .= "\n\t\t\tdocument.getElementById('" . $key . "').style.display = '" . $value . "';";
		}
		$js .= "\ndocument.field_form.field_fmt.selectedIndex = 0;\n";
		$js .= "\t\t}";

		 // Add the JS
		$r = preg_replace("/(id\s*==\s*.rel.*?})/is", $js, $r);

		// -- If existing field, select the proper blocks
		if (isset($data["field_type"]) && $data["field_type"] == $this->type)
		{

			foreach ($items as $key => $value)
			{
				preg_match('/(id=.' . $key . '.*?display:\s*)block/', $r, $match);

				// look for a block
				if (count($match) > 0 && $value == "none")
				{
					$r = str_replace($match[0], $match[1] . $value, $r);
				}
				elseif ($value == "block")
				{ // no block matches

					preg_match('/(id=.' . $key . '.*?display:\s*)none/', $r, $match);

					if (count($match) > 0)
					{
						$r = str_replace($match[0], $match[1] . $value, $r);
					}
				}
			}
		}

		return $r;
	}

	/**
	 * Add the URL field to the publish / edit form.
	 *
	 * @param string $row Parameters for the field from the database
	 * @param string $field_data If entry is not new, this will have field's current value
	 * @return  string Modified control panel html containing the field data
	 * @since   Version 1.0.0
	 */
	function display_url_field($row, $field_data)
	{

		global $DSP, $EXT, $PREFS, $LANG;
		$LANG->fetch_language_file('dc_url_field');

		$r = '';

		// Check if someone else is using this hook
		if ($EXT->last_call !== FALSE)
		{
		   $r = $EXT->last_call;
		}

		if ($row['field_type'] == $this->type)
		{

			//  =============================================
			//  CSS and JS
			//  =============================================
			if (stristr($DSP->extra_header, "function check_url_field") === false)
			{
			  $DSP->extra_header .= $this->_js();
			}

			$field_id = 'field_id_' . $row['field_id'];

			$r .= $DSP->input_text($field_id, $field_data, 10, 128, 'dc_url_field', $this->settings['field_width']);
			$r .= '<a href="javascript:void(0)" onclick="check_url_field( \'' . $field_id . '\' )">';
			$r .= '<img src="' . $PREFS->core_ini['site_url'] . $this->settings['field_icon'] . '" style="border:none;" alt="' . $LANG->line('icon_alt') . '" />';
			$r .= '</a>';
		}

		return $r;
	}

	//  ========================================================================
	//  Private Functions
	//  ========================================================================

	/**
	 * Render javascript functions.
	 *
	 * @access private
	 * @param NULL
	 * @return string
	 */
	function _js()
	{
		global $LANG;
		$LANG->fetch_language_file('dc_url_field');

		return "
<script type=\"text/javascript\" charset=\"utf-8\">
function check_url_field( id )
{
	var url_field = document.getElementById( id );
	var url_value = url_field.value;

	if(url_value == '')
	{
		alert( '" . $LANG->line('error_empty') . "' );
	}
	else if(url_value.indexOf('http://') != 0)
	{
		alert( '" . $LANG->line('error_protocol') . "' );
	}
	//all went well, we're good to go and open the new window
	else
	{
		window.open(url_value, 'URL Check').focus();
	}

	return false;
}
</script>
		";
	}

}
//END CLASS
?>
