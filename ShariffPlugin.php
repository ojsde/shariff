<?php

/**
 * @file plugins/generic/shariff/ShariffPlugin.inc.php
 *
 * Copyright (c) 2023 Universitätsbibliothek Freie Universität Berlin
 * Distributed under the GNU GPL v3. For full terms see the file LICENSE.
 *
 * @class ShariffPlugin
 * @ingroup plugins_generic_shariff
 *
 * @brief Shariff plugin class
 */

namespace APP\plugins\generic\shariff;

define('SHARIFF_VERSION', '3.3.1');

use APP\core\Application;
use APP\template\TemplateManager;
use PKP\facades\Locale;
use PKP\plugins\GenericPlugin;
use PKP\plugins\Hook;
use PKP\plugins\PluginRegistry;
use PKP\linkAction\LinkAction;
use PKP\linkAction\request\RedirectAction;

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

					Hook::add('Template::Settings::website::appearance', array($this, 'callbackAppearanceTab')); //to enable display of plugin settings tab
					Hook::add('Schema::get::context', array($this, 'addToSchema')); // to add Shariff avriables to context schema

					Hook::add('Templates::Common::Footer::PageFooter', array($this, 'addShariffButtons'));
					Hook::add('Templates::Article::Details', array($this, 'addShariffButtons'));
					Hook::add('Templates::Catalog::Book::Details', array($this, 'addShariffButtons'));
					Hook::add('Templates::Preprint::Details', array($this, 'addShariffButtons'));

					Hook::add('TemplateManager::display', function($hookName, $args) {
						// This needs to be done within the display hook, because OJS only loads our context varaiables after the plugin is registered
						// and we need them to decide whether to load other resources
						$templateMgr = $args[0];
						
						$request = Application::get()->getRequest();
						$context = $request->getContext();
						$baseUrl = $request->getBaseUrl();

						if ($context->getData('shariffEnableWCAG')===NULL?true:(bool)$context->getData('shariffEnableWCAG')) {
							$wcagCssUrl = $baseUrl .'/' . $this->getPluginPath() .'/css/wcag-themes.css';
							$templateMgr->addStyleSheet('shariffPluginStylesWCAGCSS', $wcagCssUrl);
						}

						if (strcmp($context->getData('shariffPositionSelected'),'sidebar') == 0) {
							// Load this plugin as a block plugin as well (for sidebar)
							$shariffBlockPlugin = new ShariffBlockPlugin($this->getName(), $this->getPluginPath());
							PluginRegistry::register(
								'blocks',
								$shariffBlockPlugin,
								$this->getPluginPath()
							);
						}
					});

					// load standard resources
					$baseUrl = $request->getBaseUrl();
					$templateMgr = TemplateManager::getManager($request);
					$jsUrl = $baseUrl .'/'. $this->getPluginPath().'/shariff-'.SHARIFF_VERSION.'/shariff.complete.js';
					$templateMgr->addJavaScript('shariffPluginJavascriptShariff', $jsUrl);

					$shariffCssUrl = $baseUrl .'/' . $this->getPluginPath() . '/shariff-'.SHARIFF_VERSION.'/shariff.complete.css';
					$templateMgr->addStyleSheet('shariffPluginStylesShariff', $shariffCssUrl);

					$cssUrl = $baseUrl .'/' . $this->getPluginPath() . '/css/shariff.css';
					$templateMgr->addStyleSheet('shariffPluginStylesCSS', $cssUrl);
				}
			}
			return $success;
	}

    /**
     * @copydoc Plugin::getActions()
     */
    public function getActions($request, $verb): array
    {
        $actions = parent::getActions($request, $verb);
        if (!$this->getEnabled()) {
            return $actions;
        }

		$dispatcher = $request->getDispatcher();
        array_unshift($actions, new LinkAction('settings', new RedirectAction($dispatcher->url(
			$request,
			Application::ROUTE_PAGE,
			null,
			'management',
			'settings',
			['website'],
			[''],
			'shariffPlugin'
		)), __('manager.plugins.settings')));
        return $actions;
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
		$schema->properties->{"shariffEnableWCAG"} = (object) [
			'type' => 'boolean',
			'apiSummary' => true,
			'validation' => ['nullable'],
		];
		$schema->properties->{"shariffShowBlockHeading"} = (object) [
			'type' => 'boolean',
			'apiSummary' => true,
			'validation' => ['nullable'],
		];
		$schema->properties->{"shariffPublicationSharingLink"} = (object) [
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
		$request =& Application::get()->getRequest();
		$context = $request->getContext();
		$dispatcher = $request->getDispatcher();

		# url to handle form dialog (we add our vars to the context schema)
		$contextApiUrl = $dispatcher->url(
			$request,
			Application::ROUTE_API,
			$context->getPath(),
			'contexts/' . $context->getId()
		);

		// instantinate settings form
		$shariffSettingsForm = new ShariffSettingsForm($contextApiUrl, $context->getSupportedLocaleNames(), $context);

		// setup template
		$templateMgr->setConstants([
			'FORM_SHARIFF_SETTINGS',
		]);

		$state = $templateMgr->getTemplateVars('state');
		$state['components'][FORM_SHARIFF_SETTINGS] = $shariffSettingsForm->getConfig();
		$templateMgr->assign('state', $state);

		$output .= $templateMgr->display($this->getTemplateResource('settingsForm.tpl'));

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
		return parent::getTemplatePath($inCore);
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

		// display the buttons depending on the selected position
		switch ($context->getData('shariffPositionSelected')) {
			case 'footer':
				if ($hookName != 'Templates::Common::Footer::PageFooter') return false;
				break;
			case 'submission':
				if (($hookName != 'Templates::Article::Details') &&
					($hookName != 'Templates::Catalog::Book::Details') &&
					($hookName != 'Templates::Preprint::Details')) return false;
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

		if ($dataServicesString != "") {

			// theme
			$selectedTheme = $context->getData('shariffThemeSelected');

			// orientation
			$selectedOrientation = $context->getData('shariffOrientationSelected');

			// get language from system
			$locale = Locale::getLocale();

			$publicationSharingLink = $context->getData('shariffPublicationSharingLink');
			if ($publicationSharingLink == 'doiUrl') {
				$publication = $template->getTemplateVars('currentPublication');
				if ($publication && $publication->getData('doiObject')) {
					$doiUrl = $publication->getData('doiObject')->getResolvingUrl();
				}
			}

			// urls
			$baseUrl = $request->getBaseUrl();
			$requestedUrl = $doiUrl ?: $request->getCompleteUrl();
			$backendUrl = $baseUrl .'/'. 'shariff-backend';
			
			// assign variables to the templates
			$templateMgr = TemplateManager::getManager($request);
    		$templateMgr->assign('dataServicesString', $dataServicesString);
    		$templateMgr->assign('selectedTheme', $selectedTheme);
			$templateMgr->assign('shariffPostionSelected', $context->getData('shariffPositionSelected'));
    		$templateMgr->assign('selectedOrientation', $selectedOrientation);
    		$templateMgr->assign('backendUrl', $backendUrl);
    		$templateMgr->assign('iso1Lang', $locale);
    		$templateMgr->assign('requestedUrl', $requestedUrl);
			$templateMgr->assign('showBlockHeading', $context->getData('shariffShowBlockHeading'));

			$output .= $templateMgr->fetch($this->getTemplateResource('shariff.tpl'));
		}

		return false;
	}
}

?>
