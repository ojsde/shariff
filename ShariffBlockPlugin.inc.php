<?php

/**
 * @file plugins/generic/shariff/ShariffBlockPlugin.inc.php
 *
 * Copyright (c) 2014-2017 Simon Fraser University
 * Copyright (c) 2003-2017 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class ShariffBlockPlugin
 * @ingroup plugins_generic_shariff
 *
 * @brief Class for block component of shariff plugin
 */

import('lib.pkp.classes.plugins.BlockPlugin');

class ShariffBlockPlugin extends BlockPlugin {
	/** @var string Name of parent plugin */
	var $parentPluginName;

	function __construct($parentPluginName) {
		parent::__construct();
		$this->parentPluginName = $parentPluginName;
	}

	/**
	 * Get the name of this plugin. The name must be unique within
	 * its category.
	 * @return String name of plugin
	 */
	function getName() {
		return 'ShariffBlockPlugin';
	}

	/**
	 * Hide this plugin from the management interface (it's subsidiary)
	 */
	function getHideManagement() {
		return true;
	}

	/**
	 * Get the display name of this plugin.
	 * @return String
	 */
	function getDisplayName() {
		return __('plugins.generic.shariff.block.displayName');
	}

	/**
	 * Get a description of the plugin.
	 */
	function getDescription() {
		return __('plugins.generic.shariff.block.description');
	}

	/**
	 * Get the supported contexts (e.g. BLOCK_CONTEXT_...) for this block.
	 * @return array
	 */
	function getSupportedContexts() {
		return array(BLOCK_CONTEXT_SIDEBAR);
	}

	/**
	 * Get the web feed plugin
	 * @return ShariffPlugin
	 */
	function getShariffPlugin() {
		return PluginRegistry::getPlugin('generic', $this->parentPluginName);
	}

	/**
	 * Override the builtin to get the correct plugin path.
	 * @return string
	 */
	function getPluginPath() {
		return $this->getShariffPlugin()->getPluginPath();
	}

	/**
	 * @copydoc PKPPlugin::getTemplatePath
	 */
	function getTemplatePath($inCore = false) {
		return $this->getShariffPlugin()->getTemplatePath($inCore);
	}


	/**
	 * @copydoc BlockPlugin::getBlockTemplateFilename()
	 */
	function getBlockTemplateFilename() {
		// Return the opt-out template.
		return 'shariffBlock.tpl';
	}


	/**
	 * Get the HTML contents for this block.
	 * @param $templateMgr object
	 * @param $request PKPRequest
	 * @return $string
	 */
	function getContents($templateMgr, $request = null) {
		$context = $request->getContext();
		$contextId = $context->getId();
		$plugin = $this->getShariffPlugin();

		// services
		$selectedServices = $plugin->getSetting($contextId, 'selectedServices');
		$preparedServices = array_map(create_function('$arrayElement', 'return \'"\'.$arrayElement.\'"\';'), $selectedServices);
		$dataServicesString = implode(",", $preparedServices);

		// theme
		$selectedTheme = $plugin->getSetting($contextId, 'selectedTheme');

		// get language from system
		$locale = AppLocale::getLocale();
		$iso1Lang = AppLocale::getIso1FromLocale($locale);

		// javascript, css and backend url
		$requestedUrl = $request->getCompleteUrl();
		$baseUrl = $request->getBaseUrl();
		$jsUrl = $baseUrl .'/'. $this->getPluginPath().'/shariff.complete.js';
		$cssUrl = $baseUrl .'/' . $this->getPluginPath() . '/' . 'shariff.complete.css';
		$backendUrl = $baseUrl .'/'. 'shariff-backend';

		// assign variables to the templates
		$templateMgr->assign('dataServicesString', $dataServicesString);
		$templateMgr->assign('selectedTheme', $selectedTheme);
		$templateMgr->assign('backendUrl', $backendUrl);
		$templateMgr->assign('iso1Lang', $iso1Lang);
		$templateMgr->assign('requestedUrl', $requestedUrl);
		$templateMgr->assign('jsUrl', $jsUrl);
		$templateMgr->assign('cssUrl', $cssUrl);

		return parent::getContents($templateMgr, $request);
	}
}

?>
