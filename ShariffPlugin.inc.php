<?php

/**
 * @file plugins/generic/shariff/ShariffPlugin.inc.php
 *
 * Copyright (c) 2021 Universitätsbibliothek Freie Universität Berlin
 * Distributed under the GNU GPL v3. For full terms see the file LICENSE.
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
	function register($category, $path, $mainContextId = null) {
		$success = parent::register($category, $path, $mainContextId);
			if ($success && $this->getEnabled($mainContextId)) {
				$request = $this->getRequest();
				$context = $request->getContext();
				if ($context) {
					$contextId = $context->getId();

					HookRegistry::register('Template::Settings::website::appearance', array($this, 'callbackAppearanceTab')); //to enable display of plugin settings tab
					HookRegistry::register('Schema::get::context', array($this, 'addToSchema')); // to add Shariff avriables to context schema

					HookRegistry::register('Templates::Common::Footer::PageFooter', array($this, 'addShariffButtons'));
					HookRegistry::register('Templates::Article::Details', array($this, 'addShariffButtons'));
					HookRegistry::register('Templates::Catalog::Book::Details', array($this, 'addShariffButtons'));
					HookRegistry::register('Templates::Preprint::Details', array($this, 'addShariffButtons'));
					
					// Load this plugin as a block plugin as well (for sidebar)
					$this->import('ShariffBlockPlugin');
					$shariffBlockPlugin = new ShariffBlockPlugin($this->getName(), $this->getPluginPath());
					PluginRegistry::register(
						'blocks',
						$shariffBlockPlugin,
						$this->getPluginPath()
					);
				}
			}
			return $success;
	}

	public function addToSchema($hookName, $params) {
		$schema =& $params[0];

		$schema->properties->{"shariffServicesSelected"} = (object) [
			'type' => 'array',
			'apiSummary' => true,
			'validation' => ['nullable'],
			'items' => (object) ['type' => 'string']
		];
		$schema->properties->{"shariffThemeSelected"} = (object) [
			'type' => 'string',
			'apiSummary' => true,
			'validation' => ['nullable'],
		];
		$schema->properties->{"shariffPositionSelected"} = (object) [
			'type' => 'string',
			'apiSummary' => true,
			'validation' => ['nullable'],
		];
		$schema->properties->{"shariffOrientationSelected"} = (object) [
			'type' => 'string',
			'apiSummary' => true,
			'validation' => ['nullable'],
		];

		return false;
	}

	function callbackAppearanceTab($hookName, $args) {		

		# prepare data
		$templateMgr =& $args[1];
		$output =& $args[2];
		$request =& Registry::get('request');
		$context = $request->getContext();
		$contextId = $context->getId();
		$dispatcher = $request->getDispatcher();

		# url to handle form dialog (we add our vars to the context schema)
		$contextApiUrl = $dispatcher->url(
			$request,
			ROUTE_API,
			$context->getPath(),
			'contexts/' . $context->getId()
		);
		$contextUrl = $request->getRouter()->url($request, $context->getPath());

		// instantinate settings form
		$this->import('ShariffSettingsForm');
		$shariffSettingsForm = new ShariffSettingsForm($contextApiUrl, $context->getSupportedLocaleNames(), $context);

		// setup template
		$templateMgr->setConstants([
			'FORM_SHARIFF_SETTINGS',
		]);

		$state = $templateMgr->getTemplateVars('state');
		$state['components'][FORM_SHARIFF_SETTINGS] = $shariffSettingsForm->getConfig();
		$templateMgr->assign('state', $state); // In OJS 3.3 $templateMgr->setState doesn't seem to update template vars anymore

		$output .= $templateMgr->display($this->getTemplateResource('settingsForm.tpl'));

		// Permit other plugins to continue interacting with this hook
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
	 * Hook callback: Handle requests.
	 * @param $hookName string The name of the hook being invoked
	 * @param $args array The parameters to the invoked hook
	 * @return bool
	 */
	function addShariffButtons($hookName, $args) {
		$request = $this->getRequest();
		$context = $request->getContext();

		// display the buttons depending in the selected position
		switch ($context->getData('shariffPositionSelected')) {
			case 'footer':
				if ($hookName != 'Templates::Common::Footer::PageFooter') return false;
				break;
			case 'submission':
				if (($hookName != 'Templates::Article::Details') &&
					($hookName !=  'Templates::Catalog::Book::Details') &&
					($hookName !=  'Templates::Preprint::Details')) return false;
				break;
			default:
				return false;
		}

		// create the template
		$template =& $args[1];
		$output =& $args[2];

		// services
		$selectedServices = $context->getData('shariffServicesSelected');
		
		$preparedServices = array_map(function($arrayElement){return $arrayElement;}, $selectedServices);
		$dataServicesString = implode(",", $preparedServices);

		// theme
		$selectedTheme = $context->getData('shariffThemeSelected');

		// orientation
		$selectedOrientation = $context->getData('shariffOrientationSelected');

		// get language from system
		$locale = AppLocale::getLocale();
		$iso1Lang = AppLocale::getIso1FromLocale($locale);

		// javascript, css and backend url
		$requestedUrl = $request->getCompleteUrl();
		$request = new PKPRequest();
		$baseUrl = $request->getBaseUrl();
		$jsUrl = $baseUrl .'/'. $this->getPluginPath().'/shariff-3.2.1/shariff.complete.js';
		$cssUrl = $baseUrl .'/' . $this->getPluginPath() . '/' . 'shariff-3.2.1/shariff.complete.css';
		$backendUrl = $baseUrl .'/'. 'shariff-backend';

		$selectedPositon = $context->getData('shariffPositionSelected');
		if ($selectedPositon == 'footer') {
		    $divWrapper = '<div class="pkp_structure_footer_wrapper"><div class="pkp_structure_footer">';
		} elseif ($selectedPositon == 'submission') {
		    $divWrapper = '<div><div>';
		}
		
		$output .= '
			<link rel="stylesheet" type="text/css" href="'.$cssUrl.'">'.$divWrapper.'
			<div class="shariff item" data-lang="'. $iso1Lang.'"
				data-services="['.$dataServicesString.']"
				data-mail-url="mailto:"
				data-mail-body={url}
				data-backend-url="'.$backendUrl.'"
				data-theme="'.$selectedTheme.'"
				data-orientation="'.$selectedOrientation.'"
				data-url="'. $requestedUrl .'">
			</div>
            </div>
            </div>
			<script src="'.$jsUrl.'"></script>';

		return false;
	}
}

?>
