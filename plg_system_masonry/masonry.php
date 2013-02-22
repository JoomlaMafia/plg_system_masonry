<?php defined('_JEXEC') or die;
/**
 * @copyright   Copyright (C) 2013 mktgexperts.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('JPATH_BASE') or die;

/**
 * Add to doc plugin class.
 *
 */
class plgSystemAddToDoc extends JPlugin {

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
			// add script delaration
			if ($this->params->get('enable_script_declaration') && trim($this->params->get('script_declaration'))) {
				$doc->addScriptDeclaration($this->params->get('script_declaration'));
			}
			// add style declaration
			if ($this->params->get('enable_style_declaration') && trim($this->params->get('style_declaration'))) {
				$doc->addStyleDeclaration($this->params->get('style_declaration'));
			}
			// add script files
			if ($this->params->get('enable_script_files') && trim($this->params->get('script_files'))) {
				$files = explode("\n", trim($this->params->get('script_files')));
				foreach ($files as $file) {
					if (trim($file)) $doc->addScript(trim($file));
				}
			}
			// add style files
			if ($this->params->get('enable_style_files') && trim($this->params->get('style_files'))) {
				$files = explode("\n", trim($this->params->get('style_files')));
				foreach ($files as $file) {
					if (trim($file)) $doc->addStyleSheet(trim($file));
				}
			}
		}
	}

	function onAfterRender() {
		$app = & JFactory::getApplication();
		if (($app->getName() == $this->params->get('execution_side')) || $this->params->get('execution_side') == "both") {
			//require_once JPATH_BASE."/media/plg_system_addtodoc/lib/simple_html_dom.php";
			//$pageDOM = new simple_html_dom();
			//$pageDOM->load(JResponse::getBody());
			$page = JResponse::getBody();
			// prepend to head
			if ($this->params->get('enable_prepend_to_head') && trim($this->params->get('prepend_to_head'))) {
				//$pageDOM->find('head', 0)->innertext = $this->params->get('prepend_to_head') . $pageDOM->find('head', 0)->innertext;
				//JResponse::setBody((string) $pageDOM->save());
				$page = str_replace("<head>", "<head>" . $this->params->get('prepend_to_head'), $page);
			}
			// append to head
			if ($this->params->get('enable_append_to_head') && trim($this->params->get('append_to_head'))) {
				//$pageDOM->find('head', 0)->innertext = $pageDOM->find('head', 0)->innertext . $this->params->get('append_to_head');
				//JResponse::setBody((string) $pageDOM->save());
				$page = str_replace("</head>", $this->params->get('append_to_head') . "</head>", $page);
			}
			// prepend to body
			if ($this->params->get('enable_prepend_to_body') && trim($this->params->get('prepend_to_body'))) {
				//$pageDOM->find('body', 0)->innertext = $this->params->get('prepend_to_body') . $pageDOM->find('body', 0)->innertext;
				//JResponse::setBody((string) $pageDOM->save());
				preg_match_all('/<body[^>]*>/i', $page, $bodyOpen);
				$bodyOpen = $bodyOpen[0][0];
				$page = str_replace($bodyOpen, $bodyOpen . $this->params->get('prepend_to_body'), $page);
			}
			// append to body
			if ($this->params->get('enable_append_to_body') && trim($this->params->get('append_to_body'))) {
				//$pageDOM->find('body', 0)->innertext = $pageDOM->find('body', 0)->innertext . $this->params->get('append_to_body');
				//JResponse::setBody((string) $pageDOM->save());
				$page = str_replace("</body>", $this->params->get('append_to_body') . "</body>", $page);
			}
			//$pageDOM->clear();
			//unset($pageDOM);
			JResponse::setBody($page);
		}
	}
}
