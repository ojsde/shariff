<?php

/**
 * @file plugins/generic/shariff/ShariffSettingsForm.inc.php
 *
 * Copyright (c) 2014-2017 Simon Fraser University
 * Copyright (c) 2003-2017 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class ShariffSettingsForm
 * @ingroup plugins_generic_shariff
 *
 * @brief Form for managers to modify web feeds plugin settings
 */

import('lib.pkp.classes.form.Form');

class ShariffSettingsForm extends Form {

	/** @var int Associated context ID */
	private $_contextId;

	/** @var ShariffPlugin Web feed plugin */
	private $_plugin;

	/**
	 * Constructor
	 * @param $plugin ShariffPlugin Web feed plugin
	 * @param $contextId int Context ID
	 */
	function __construct($plugin, $contextId) {
		$this->_contextId = $contextId;
		$this->_plugin = $plugin;

		parent::__construct($plugin->getTemplatePath() . 'settingsForm.tpl');
		$this->addCheck(new FormValidatorPost($this));
		$this->addCheck(new FormValidatorCSRF($this));
	}

	/**
	 * Initialize form data.
	 */
	function initData() {
		$contextId = $this->_contextId;
		$plugin = $this->_plugin;

		// array of possible social media services
		$services = array(
			array("twitter" => "plugins.generic.shariff.settings.service.twitter"),
			array("facebook" => "plugins.generic.shariff.settings.service.facebook"),
			array("googleplus" => "plugins.generic.shariff.settings.service.googleplus"),
			array("linkedin" => "plugins.generic.shariff.settings.service.linkedin"),
			array("mail" => "plugins.generic.shariff.settings.service.mail"),
			array("pinterest" => "plugins.generic.shariff.settings.service.pinterest"),
			array("whatsapp" => "plugins.generic.shariff.settings.service.whatsapp"),
			array("xing" => "plugins.generic.shariff.settings.service.xing"),
			array("addthis" => "plugins.generic.shariff.settings.service.addthis"),
			array("info" => "plugins.generic.shariff.settings.service.info")
		);
		$this->setData('services', $services);
		$this->setData('selectedServices', $plugin->getSetting($contextId, 'selectedServices'));

		// array of available themes
		$themes = array(
			'standard' => 'plugins.generic.shariff.settings.theme.standard',
			'grey' => 'plugins.generic.shariff.settings.theme.grey',
			'white' => 'plugins.generic.shariff.settings.theme.white'
		);
		$this->setData('themes', $themes);
		$this->setData('selectedTheme', $plugin->getSetting($contextId, 'selectedTheme'));

		// array of possible positions at the website
		$positions = array(
			'footer' => 'plugins.generic.shariff.settings.position.footer',
			'sidebar' => 'plugins.generic.shariff.settings.position.sidebar'
		);
		$this->setData('positions', $positions);
		$this->setData('selectedPosition', $plugin->getSetting($contextId, 'selectedPosition'));
	}

	/**
	 * Assign form data to user-submitted data.
	 */
	function readInputData() {
		$this->readUserVars(array('selectedTheme', 'selectedPosition', 'selectedServices'));
	}

	/**
	 * Fetch the form.
	 * @copydoc Form::fetch()
	 */
	function fetch($request) {
		$templateMgr = TemplateManager::getManager($request);
		$templateMgr->assign('pluginName', $this->_plugin->getName());
		return parent::fetch($request);
	}

	/**
	 * Save settings.
	 */
	function execute() {
		$plugin = $this->_plugin;
		$contextId = $this->_contextId;

		$plugin->updateSetting($contextId, 'selectedTheme', $this->getData('selectedTheme'));
		$plugin->updateSetting($contextId, 'selectedPosition', $this->getData('selectedPosition'));
		$plugin->updateSetting($contextId, 'selectedServices', $this->getData('selectedServices'), 'object');
	}
}
?>
