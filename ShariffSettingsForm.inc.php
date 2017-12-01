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
			array("pinterest" => "plugins.generic.shariff.settings.service.pinterest"),
			array("xing" => "plugins.generic.shariff.settings.service.xing"),
			array("whatsapp" => "plugins.generic.shariff.settings.service.whatsapp"),
			array("addthis" => "plugins.generic.shariff.settings.service.addthis"),
			array("tumblr" => "plugins.generic.shariff.settings.service.tumblr"),
			array("flattr" => "plugins.generic.shariff.settings.service.flattr"),
			array("diaspora" => "plugins.generic.shariff.settings.service.diaspora"),
			array("reddit" => "plugins.generic.shariff.settings.service.reddit"),
			array("stumbleupon" => "plugins.generic.shariff.settings.service.stumbleupon"),
			array("threema" => "plugins.generic.shariff.settings.service.threema"),
			array("weibo" => "plugins.generic.shariff.settings.service.weibo"),
			array("tencent-weibo" => "plugins.generic.shariff.settings.service.tencent-weibo"),
			array("qzone" => "plugins.generic.shariff.settings.service.qzone"),
			array("mail" => "plugins.generic.shariff.settings.service.mail"),
			array("print" => "plugins.generic.shariff.settings.service.print"),
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
			'sidebar' => 'plugins.generic.shariff.settings.position.sidebar',
			'submission' => 'plugins.generic.shariff.settings.position.submission'
		);
		$this->setData('positions', $positions);
		$this->setData('selectedPosition', $plugin->getSetting($contextId, 'selectedPosition'));

		// array of possible orientations
		$orientations = array(
			'vertical' => 'plugins.generic.shariff.settings.orientation.vertical',
			'horizontal' => 'plugins.generic.shariff.settings.orientation.horizontal'
		);
		$this->setData('orientations', $orientations);
		$this->setData('selectedOrientation', $plugin->getSetting($contextId, 'selectedOrientation'));

	}

	/**
	 * Assign form data to user-submitted data.
	 */
	function readInputData() {
		$this->readUserVars(array('selectedTheme', 'selectedPosition', 'selectedServices', 'selectedOrientation'));
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
		$plugin->updateSetting($contextId, 'selectedOrientation', $this->getData('selectedOrientation'));
		$plugin->updateSetting($contextId, 'selectedServices', $this->getData('selectedServices'), 'object');
	}
}
?>
