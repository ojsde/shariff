<?php

/**
 * @file plugins/generic/shariff/ShariffPlugin.inc.php
 *
 * Copyright (c) 2018 Center for Digital Systems (CeDiS), Freie Universität Berlin
 * Distributed under the GNU GPL v2. For full terms see the file LICENSE.
 *
 * @class ShariffPlugin
 * @ingroup plugins_generic_shariff
 *
 * @brief Shariff plugin class
 */

import('lib.pkp.classes.plugins.GenericPlugin');

class ShariffPlugin extends GenericPlugin {
	/**
	 * @copydoc Plugin::getDisplayName()
	 */
	function getDisplayName() {
		return __('plugins.generic.shariff.displayName');
	}

	/**
	 * @copydoc Plugin::getDescription()
	 */
	function getDescription() {
		return __('plugins.generic.shariff.description');
	}

	/**
	 * @copydoc Plugin::register()
	 */
	function register($category, $path) {

		if (parent::register($category, $path)) {
			if ($this->getEnabled()) {

				$request = $this->getRequest();
				$context = $request->getContext();
				$contextId = $context->getId();

				// display the buttons depending in the selected position
				switch($this->getSetting($contextId, 'selectedPosition')){
					case 'footer':
						HookRegistry::register('Templates::Common::Footer::PageFooter', array($this, 'addShariffButtons'));
						break;
					case 'sidebar':
						HookRegistry::register('PluginRegistry::loadCategory', array($this, 'callbackLoadCategory'));
						break;
					case 'submission':
						HookRegistry::register('Templates::Article::Details', array($this, 'addShariffButtons'));
						HookRegistry::register('Templates::Catalog::Book::Details', array($this, 'addShariffButtons'));
				}
			}
			return true;
		}
		return false;
	}

	/**
	 * Get the name of the settings file to be installed on new context
	 * creation.
	 * @return string
	 */
	function getContextSpecificPluginSettingsFile() {
		return $this->getPluginPath() . '/settings.xml';
	}

	/**
	 * @copydoc Plugin::getTemplatePath()
	 */
	function getTemplatePath($inCore = false) {
		return parent::getTemplatePath($inCore) . 'templates/';
	}

	/**
	 * Register as a block plugin, even though this is a generic plugin.
	 * This will allow the plugin to behave as a block plugin, i.e. to
	 * have layout tasks performed on it.
	 * @param $hookName string
	 * @param $args array
	 * @return bool
	 */
	function callbackLoadCategory($hookName, $args) {
		$category =& $args[0];
		$plugins =& $args[1];
		switch ($category) {
			case 'blocks':
				$this->import('ShariffBlockPlugin');
				$blockPlugin = new ShariffBlockPlugin($this->getName());
				$plugins[$blockPlugin->getSeq()][$blockPlugin->getPluginPath()] = $blockPlugin;
				break;
		}
		return false;
	}

	/**
	 * Hook callback: Handle requests.
	 * @param $hookName string The name of the hook being invoked
	 * @param $args array The parameters to the invoked hook
	 * @return bool
	 */
	function addShariffButtons($hookName, $args) {
		$template =& $args[1];
		$output =& $args[2];

		$request = $this->getRequest();
		$context = $request->getContext();
		$contextId = $context->getId();

		// services
		$selectedServices = $this->getSetting($contextId, 'selectedServices');
		$preparedServices = array_map(create_function('$arrayElement', 'return \'&quot;\'.$arrayElement.\'&quot;\';'), $selectedServices);
		$dataServicesString = implode(",", $preparedServices);

		// theme
		$selectedTheme = $this->getSetting($contextId, 'selectedTheme');

		// orientation
		$selectedOrientation = $this->getSetting($contextId, 'selectedOrientation');

		// get language from system
		$locale = AppLocale::getLocale();
		$iso1Lang = AppLocale::getIso1FromLocale($locale);

		// javascript, css and backend url
		$requestedUrl = $request->getCompleteUrl();
		$baseUrl = Request::getBaseUrl();
		$jsUrl = $baseUrl .'/'. $this->getPluginPath().'/shariff/shariff.complete.js';
		$cssUrl = $baseUrl .'/' . $this->getPluginPath() . '/' . 'shariff/shariff.complete.css';
		$backendUrl = $baseUrl .'/'. 'shariff-backend';

		$output .= '
			<link rel="stylesheet" type="text/css" href="'.$cssUrl.'">
			<div class="shariff pkp_footer_content" data-lang="'. $iso1Lang.'"
				data-services="['.$dataServicesString.']"
				data-mail-url="mailto:"
				data-mail-body={url}
				data-backend-url="'.$backendUrl.'"
				data-theme="'.$selectedTheme.'"
				data-orientation="'.$selectedOrientation.'"
				data-url="'. $requestedUrl .'">
			</div>
			<script src="'.$jsUrl.'"></script>';

		return false;
	}

	/**
	 * @copydoc Plugin::getActions()
	 */
	function getActions($request, $verb) {
		$router = $request->getRouter();
		import('lib.pkp.classes.linkAction.request.AjaxModal');
		return array_merge(
			$this->getEnabled()?array(
				new LinkAction(
					'settings',
					new AjaxModal(
						$router->url($request, null, null, 'manage', null, array('verb' => 'settings', 'plugin' => $this->getName(), 'category' => 'generic')),
						$this->getDisplayName()
					),
					__('manager.plugins.settings'),
					null
				),
			):array(),
			parent::getActions($request, $verb)
		);
	}

	/**
	 * @copydoc Plugin::manage()
	 */
	function manage($args, $request) {
		switch ($request->getUserVar('verb')) {
			case 'settings':
				AppLocale::requireComponents(LOCALE_COMPONENT_APP_COMMON,  LOCALE_COMPONENT_PKP_MANAGER);
				$this->import('ShariffSettingsForm');
				$form = new ShariffSettingsForm($this, $request->getContext()->getId());

				if ($request->getUserVar('save')) {
					$form->readInputData();
					if ($form->validate()) {
						$form->execute();
						$notificationManager = new NotificationManager();
						$notificationManager->createTrivialNotification($request->getUser()->getId());
						return new JSONMessage(true);
					}
				} else {
					$form->initData();
				}
				return new JSONMessage(true, $form->fetch($request));
		}
		return parent::manage($args, $request);
	}
}

?>
