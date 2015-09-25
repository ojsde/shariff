<?php

/**
 * @file ShariffSettingsForm.inc.php
 *
 * Author: Božana Bokan, Center for Digital Systems (CeDiS), Freie Universität Berlin
 * Last update: September 24, 2015
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @package plugins.generic.shariff
 * @class ShariffSettingsForm
 *
 * @brief Settings form for the Shariff plugin
 */

import('lib.pkp.classes.form.Form');

class ShariffSettingsForm extends Form {
	/** @var $journalId int */
	var $journalId;

	/** @var $plugin object */
	var $plugin;

	/**
	 * Constructor.
	 * @param $plugin object
	 * @param $journalId int
	 */
	function ShariffSettingsForm($plugin, $journalId) {
		$this->journalId = $journalId;
		$this->plugin =& $plugin;

		parent::Form($plugin->getTemplatePath() . 'settingsForm.tpl');

		// Validation checks for this form
		$this->addCheck(new FormValidator($this, 'selectedServices', 'required', 'plugins.generic.shariff.form.selectedServicesRequired'));
		$this->addCheck(new FormValidator($this, 'position', 'required', 'plugins.generic.shariff.form.positionRequired'));
		$this->addCheck(new FormValidatorPost($this));
	}

	/**
	 * Display the form.
	 */
	function display() {
		$journalId = $this->journalId;
		$plugin =& $this->plugin;

		// array of available themes
		$themes = array(
			SHARIFF_THEME_STANDARD => "plugins.generic.shariff.form.theme.standard",
			SHARIFF_THEME_GREY => "plugins.generic.shariff.form.theme.grey",
			SHARIFF_THEME_WHITE => "plugins.generic.shariff.form.theme.white"
		);

		// array of possible orientations
		$orientations = array(
			SHARIFF_ORIENTATION_V => "plugins.generic.shariff.form.orientation.vertical",
			SHARIFF_ORIENTATION_H => "plugins.generic.shariff.form.orientation.horizontal"
		);

		$templateMgr =& TemplateManager::getManager();
		$templateMgr->assign('themes', $themes);
		$templateMgr->assign('orientations', $orientations);
		$templateMgr->addJavaScript('lib/pkp/js/lib/jquery/plugins/jquery.tablednd.js');
		$templateMgr->addJavaScript('lib/pkp/js/functions/tablednd.js');
		parent::display();
	}

	/**
	 * Initialize form data from the plugin.
	 * @see Form::initData()
	 */
	function initData() {
		$journalId = $this->journalId;
		$plugin =& $this->plugin;
		foreach($this->_getFormFields() as $fieldName => $fieldType) {
			if ($fieldName == 'services') {
				$services = $plugin->getSetting($journalId, $fieldName);
				if (empty($services)) {
					$services = array(
						array("addthis" => "plugins.generic.shariff.form.service.addthis"),
						array("facebook" => "plugins.generic.shariff.form.service.facebook"),
						array("googleplus" => "plugins.generic.shariff.form.service.googleplus"),
						array("info" => "plugins.generic.shariff.form.service.info"),
						array("linkedin" => "plugins.generic.shariff.form.service.linkedin"),
						array("mail" => "plugins.generic.shariff.form.service.mail"),
						array("piterest" => "plugins.generic.shariff.form.service.pinterest"),
						array("twitter" => "plugins.generic.shariff.form.service.twitter"),
						array("whatsapp" => "plugins.generic.shariff.form.service.whatsapp"),
						array("xing" => "plugins.generic.shariff.form.service.xing")
					);
				}
				$this->setData($fieldName, $services);
			} else {
				$this->setData($fieldName, $plugin->getSetting($journalId, $fieldName));
			}
		}
	}

	/**
	 * Assign form data to user-submitted data.
	 * @see Form::readInputData()
	 */
	function readInputData() {
		$this->readUserVars(array_keys($this->_getFormFields()));
	}

	/**
	 * Save the plugin's data.
	 * @see Form::execute()
	 */
	function execute() {
		$journalId = $this->journalId;
		$plugin =& $this->plugin;
		foreach($this->_getFormFields() as $fieldName => $fieldType) {
			$plugin->updateSetting($journalId, $fieldName, $this->getData($fieldName), $fieldType);
		}
	}

	//
	// Private helper methods
	//
	/**
	 * Get all form fields and their types
	 * @return array
	 */
	function _getFormFields() {
		return array(
			'services' => 'object',
			'selectedServices' => 'object',
			'selectedTheme' => 'string',
			'selectedOrientation' => 'string',
			'backendUrl' => 'string',
			'position' => 'string'
		);
	}
}
?>
