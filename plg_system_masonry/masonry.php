<?php defined('_JEXEC') or die;
/**
 * @copyright   Copyright (C) 2013 mktgexperts.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('JPATH_BASE') or die;

/**
 * Masonry plugin class.
 *
 */
class plgSystemMasonry extends JPlugin {

	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 */
	public function __construct(& $subject, $config) {
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	function onBeforeRender() {
		$app = & JFactory::getApplication();
		if (($app->getName() == $this->params->get('execution_side')) || $this->params->get('execution_side') == "both") {
			$doc =& JFactory::getDocument();

			// add script files
			JHtml::_('behavior.framework', true);
			$doc->addScript("media/plg_system_masonry/js/masonry.js");

			// Pass parameters as global variables
			$doc->addScriptDeclaration(
				"var col_width = " . $this->_ee($this->params->get('col_width')) . "; " .
				"var col_width_tolerance = " . $this->params->get('col_width_tolerance') . "; " .
				"var wall_selector =	'" . $this->_ee($this->params->get('wall_selector')) . "'; " .
				"var brick_selector = '" . $this->_ee($this->params->get('brick_selector')) . "'; " .
				"var arrangement_mode = '" . $this->params->get('arrangement_mode') . "'; " .
				"var source_mode = '" . $this->params->get('source_mode') . "'; " .
				"var debug_mode = " . $this->params->get('debug_mode') . "; "
			);

			// add style declaration
			$wall_selector = $this->params->get('wall_selector');
			$brick_selector = $this->params->get('brick_selector');
			if ($this->params->get('enable_transitions')) {
				$doc->addStyleDeclaration("
					$wall_selector {
						-webkit-transition: height 600ms ease-out ;
						 -moz-transition: height 600ms ease-out ;
						 -ms-transition: height 600ms ease-out ;
						 -o-transition: height 600ms ease-out ;
						 transition: height 600ms ease-out ;
					}
					$brick_selector {
						-webkit-transition: left 600ms ease-out, top 500ms ease-out ;
						-moz-transition: left 600ms ease-out, top 500ms ease-out ;
						-ms-transition: left 600ms ease-out, top 500ms ease-out ;
						-o-transition: left 600ms ease-out, top 500ms ease-out ;
						transition: left 600ms ease-out, top 500ms ease-out ;
					}
				");
			}
		}
	}

	/**
	 * Method to output do HTML entity encoding and escape it.
	 *
	 * @param   string  $output  the source code.
	 *
	 * @return  string  The processed code.
	 */
	private function _ee($output) {
		// TODO: clean this mess
		$output = str_replace("'", "\'", $output);
		return htmlspecialchars($output, ENT_COMPAT, 'UTF-8');
	}

}
