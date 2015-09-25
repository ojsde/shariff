<?php

/**
 * @file ShariffPlugin.inc.php
 *
 * Author: Božana Bokan, Center for Digital Systems (CeDiS), Freie Universität Berlin
 * Last update: September 24, 2015
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @package plugins.generic.shariff
 * @class ShariffPlugin
 *
 * @brief Shariff plugin class
 */

import('lib.pkp.classes.plugins.GenericPlugin');

define('SHARIFF_POSITION_ARTICLEFOOTER', 'articlefooter');
define('SHARIFF_POSITION_ALLFOOTER', 'allfooter');
define('SHARIFF_POSITION_BLOCK', 'block');

define('SHARIFF_THEME_STANDARD', 'standard');
define('SHARIFF_THEME_GREY', 'grey');
define('SHARIFF_THEME_WHITE', 'white');

define('SHARIFF_ORIENTATION_V', 'vertical');
define('SHARIFF_ORIENTATION_H', 'horizontal');

class ShariffPlugin extends GenericPlugin {

	/**
	 * @copydoc PKPPlugin::getDisplayName()
	 */
	function getDisplayName() {
		return __('plugins.generic.shariff.displayName');
	}

	/**
	 * @copydoc PKPPlugin::getDescription()
	 */
		function getDescription() {
		return __('plugins.generic.shariff.description');
	}

	/**
	 * @copydoc PKPPlugin::register()
	 */
	function register($category, $path) {
		$success = parent::register($category, $path);
		if ($success && $this->getEnabled()) {
			$journal =& Request::getJournal();
			$journalId = $journal->getId();
			// If plug-in is set up i.e. services selected
			if ($this->getSetting($journalId, 'selectedServices')) {
				if ($this->getSetting($journalId, 'position') == SHARIFF_POSITION_BLOCK) {
					// Register shariff block plugin
					HookRegistry::register('PluginRegistry::loadCategory', array($this, 'callbackLoadCategory'));
				} elseif  ($this->getSetting($journalId, 'position') == SHARIFF_POSITION_ARTICLEFOOTER) {
					// Register for the article page footer hook
					HookRegistry::register ('Templates::Article::Footer::PageFooter', array(&$this, 'addShariffButtons'));
				} else {
					// Register for the all footer hooks
					HookRegistry::register ('Templates::Article::Footer::PageFooter', array(&$this, 'addShariffButtons'));
					HookRegistry::register ('Templates::Common::Footer::PageFooter', array(&$this, 'addShariffButtons'));
				}
				// Hook for article view -- add css in the article header template
				HookRegistry::register ('TemplateManager::display', array($this, 'handleTemplateDisplay'));
			}
		}
		return $success;
	}

	/**
	* @see PluginRegistry::loadCategory()
	*/
	function callbackLoadCategory($hookName, $args) {
		$plugin = null;
		$category = $args[0];
		if ($category ==  'blocks') {
			$this->import('ShariffBlockPlugin');
			$plugin = new ShariffBlockPlugin($this->getName());
		}
		if ($plugin) {
			$seq = $plugin->getSeq();
			$plugins =& $args[1];
			if (!isset($plugins[$seq])) $plugins[$seq] = array();
			$plugins[$seq][$this->getPluginPath()] = $plugin;
		}
		return false;
	}

	/**
	 * Hook callback: Handle requests.
	 * @param $hookName string The name of the hook being invoked
	 * @param $args array The parameters to the invoked hook
	 */
	function addShariffButtons($hookName, $args) {
		$template =& $args[1];
		$output =& $args[2];

		$journal =& Request::getJournal();
		$journalId = $journal->getId();

		// get the selected settings
		// services
		$selectedServices = $this->getSetting($journalId, 'selectedServices');
		$preparedServices = array_map(create_function('$arrayElement', 'return \'&quot;\'.$arrayElement.\'&quot;\';'), $selectedServices);
		$dataServicesString = implode(",", $preparedServices);
		// theme
		$selectedTheme = $this->getSetting($journalId, 'selectedTheme');
		// orientation
		$selectedOrientation = $this->getSetting($journalId, 'selectedOrientation');
		// backend URL
		$backendUrl = $this->getSetting($journalId, 'backendUrl');

		$locale = AppLocale::getLocale();
		$iso1Lang = AppLocale::getIso1FromLocale($locale);
		$requestedUrl = Request::getCompleteUrl();
		$baseUrl = Request::getBaseUrl();

		$output .= '
		<br /><br />
		<div class="shariff" data-lang="'. $iso1Lang.'"
		data-services="['.$dataServicesString.']"
		data-backend-url="'.$backendUrl.'"
		data-theme="'.$selectedTheme.'"
		data-orientation="' .$selectedOrientation.'"
		data-url="'. $requestedUrl .'">
		</div>
		<script src="'. $baseUrl .'/'. $this->getPluginPath().'/shariff.complete.js"></script>';

		return false;
	}

	/**
	 * Handle article view header template display.
	 */
	function handleTemplateDisplay($hookName, $params) {
		$smarty =& $params[0];
		$template =& $params[1];
		HookRegistry::register ('TemplateManager::include', array($this, 'addCss'));
		return false;
	}

	/**
	 * Add Shariff CSS to the header.
	 */
	function addCss($hookName, $args) {
		$smarty =& $args[0];
		$params =& $args[1];

		$journal =& Request::getJournal();
		$journalId = $journal->getId();
		$position = $this->getSetting($journalId, 'position');
		$baseUrl = Request::getBaseUrl();
		if (!isset($params['smarty_include_tpl_file'])) return false;
		if (($position == SHARIFF_POSITION_ARTICLEFOOTER && $params['smarty_include_tpl_file'] == 'article/header.tpl')
			|| ((($position == SHARIFF_POSITION_ALLFOOTER) || ($position == SHARIFF_POSITION_BLOCK)) && $params['smarty_include_tpl_file'] == 'core:common/header.tpl')) {
				$stylesheets = $smarty->get_template_vars('stylesheets');
				$stylesheets[] = $baseUrl . '/' . $this->getPluginPath() . '/shariff.complete.css';
				$smarty->assign('stylesheets', $stylesheets);
		}
		return false;
	}

	/**
	 * Set the page's breadcrumbs, given the plugin's tree of items
	 * to append.
	 * @param $subclass boolean
	 */
	function setBreadcrumbs($isSubclass = false) {
		$templateMgr =& TemplateManager::getManager();
		$pageCrumbs = array(
			array(
				Request::url(null, 'user'),
				'navigation.user'
			),
			array(
				Request::url(null, 'manager'),
				'user.role.manager'
			)
		);
		if ($isSubclass) $pageCrumbs[] = array(
			Request::url(null, 'manager', 'plugins'),
			'manager.plugins'
		);

		$templateMgr->assign('pageHierarchy', $pageCrumbs);
	}

	/**
	 * @copydoc PKPPlugin::getManagementVerbs()
	 */
	function getManagementVerbs() {
		$verbs = array();
		if ($this->getEnabled()) {
			$verbs[] = array('settings', __('plugins.generic.shariff.settings'));
		}
		return parent::getManagementVerbs($verbs);
	}

	/**
	 * @copydoc PKPPlugin::manage()
	 */
	function manage($verb, $args, &$message, &$messageParams) {
		if (!parent::manage($verb, $args, $message, $messageParams)) return false;

		switch ($verb) {
			case 'settings':
				$templateMgr =& TemplateManager::getManager();
				$templateMgr->register_function('plugin_url', array(&$this, 'smartyPluginUrl'));
				$journal =& Request::getJournal();

				$this->import('ShariffSettingsForm');
				$form = new ShariffSettingsForm($this, $journal->getId());
				if (Request::getUserVar('save')) {
					$form->readInputData();
					if ($form->validate()) {
						$form->execute();
						Request::redirect(null, 'manager', 'plugin');
						return false;
					} else {
						$this->setBreadCrumbs(true);
						$form->display();
					}
				} else {
					$this->setBreadCrumbs(true);
					$form->initData();
					$form->display();
				}
				return true;
			default:
				// Unknown management verb
				assert(false);
				return false;
		}
	}

	/**
	 * @copydoc Plugin::getContextSpecificPluginSettingsFile()
	 */
	function getContextSpecificPluginSettingsFile() {
		return $this->getPluginPath() . '/settings.xml';
	}

	/**
	 * @copydoc PKPPlugin::getTemplatePath()
	 */
	function getTemplatePath() {
		return parent::getTemplatePath() . 'templates/';
	}

}

?>
