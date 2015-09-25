<?php

/**
 * @file ShariffBlockPlugin.inc.php
 *
 * Author: Božana Bokan, Center for Digital Systems (CeDiS), Freie Universität Berlin
 * Last update: September 24, 2015
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @package plugins.generic.shariff
 * @class ShariffBlockPlugin
 *
 * @brief Shariff social media buttons as block plug-in.
 */

import('lib.pkp.classes.plugins.BlockPlugin');

class ShariffBlockPlugin extends BlockPlugin {

	/** @var string */
	var $_parentPluginName;

	/**
	 * Constructor
	 * @param $parentPluginName string
	 */
	function ShariffBlockPlugin($parentPluginName) {
		$this->_parentPluginName = $parentPluginName;
		parent::BlockPlugin();
	}

	//
	// Implement template methods from PKPPlugin.
	//
	/**
	 * @copydoc PKPPlugin::getHideManagement()
	 */
	function getHideManagement() {
		return true;
	}

	/**
	 * @copydoc PKPPlugin::getName()
	 */
	function getName() {
		return 'ShariffBlockPlugin';
	}

	/**
	 * @copydoc PKPPlugin::getDisplayName()
	 */
	function getDisplayName() {
		return __('plugins.generic.shariff.block.displayName');
	}

	/**
	 * @copydoc PKPPlugin::getDescription()
	 */
	function getDescription() {
		return __('plugins.generic.shariff.block.description');
	}

	/**
	 * @copydoc PKPPlugin::getPluginPath()
	 */
	function getPluginPath() {
		$plugin =& $this->_getPlugin();
		return $plugin->getPluginPath();
	}

	/**
	 * @copydoc PKPPlugin::getTemplatePath()
	 */
	function getTemplatePath() {
		$plugin =& $this->_getPlugin();
		return $plugin->getTemplatePath();
	}

	/**
	 * @copydoc PKPPlugin::getSeq()
	 */
	function getSeq() {
		// Identify the position of the faceting block.
		$seq = parent::getSeq();

		// If nothing has been configured then show the privacy
		// block after all other blocks in the context.
		if (!is_numeric($seq)) $seq = 99;

		return $seq;
	}


	//
	// Implement template methods from LazyLoadPlugin
	//
	/**
	 * @copydoc LazyLoadPlugin::getEnabled()
	 */
	function getEnabled() {
		$plugin =& $this->_getPlugin();
		return $plugin->getEnabled();
	}

	//
	// Implement template methods from BlockPlugin
	//
	/**
	 * @copydoc BlockPlugin::getBlockContext()
	 */
	function getBlockContext() {
		$blockContext = parent::getBlockContext();

		// Place the block on the right by default.
		if (!in_array($blockContext, $this->getSupportedContexts())) {
			$blockContext = BLOCK_CONTEXT_RIGHT_SIDEBAR;
		}

		return $blockContext;
	}

	/**
	 * @copydoc BlockPlugin::getBlockTemplateFilename()
	 */
	function getBlockTemplateFilename() {
		// Return the opt-out template.
		return 'shariffBlock.tpl';
	}

	/**
	 * @copydoc BlockPlugin::getContents()
	 */
	function getContents(&$templateMgr, $request) {
		$plugin =& $this->_getPlugin();
		$journal = $request->getJournal();
		$journalId = $journal->getId();

		// get the selected settings
		// services
		$selectedServices = $plugin->getSetting($journalId, 'selectedServices');
		$preparedServices = array_map(create_function('$arrayElement', 'return \'"\'.$arrayElement.\'"\';'), $selectedServices);
		$dataServicesString = implode(",", $preparedServices);
		// theme
		$selectedTheme = $plugin->getSetting($journalId, 'selectedTheme');
		// backend URL
		$backendUrl = $plugin->getSetting($journalId, 'backendUrl');

		$locale = AppLocale::getLocale();
		$iso1Lang = AppLocale::getIso1FromLocale($locale);
		$requestedUrl = $request->getCompleteUrl();
		$baseUrl = $request->getBaseUrl();
		$jsUrl = $baseUrl .'/'. $this->getPluginPath().'/shariff.complete.js';

		$templateMgr->assign('dataServicesString', $dataServicesString);
		$templateMgr->assign('selectedTheme', $selectedTheme);
		$templateMgr->assign('backendUrl', $backendUrl);
		$templateMgr->assign('iso1Lang', $iso1Lang);
		$templateMgr->assign('requestedUrl', $requestedUrl);
		$templateMgr->assign('jsUrl', $jsUrl);
		return parent::getContents($templateMgr, $request);
	}

	//
	// Private helper methods
	//
	/**
	 * Get the plugin object
	 * @return OasPlugin
	 */
	function &_getPlugin() {
		$plugin =& PluginRegistry::getPlugin('generic', $this->_parentPluginName);
		return $plugin;
	}
}

?>
