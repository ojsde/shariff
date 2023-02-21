<?php

/**
 * @file plugins/generic/shariff/ShariffSettingsForm.inc.php
 *
 * Copyright (c) 2023 Universitätsbibliothek Freie Universität Berlin
 * Distributed under the GNU GPL v3. For full terms see the file LICENSE.
 *
 * @class ShariffSettingsForm
 * @ingroup plugins_generic_shariff
 *
 * @brief Form for managers to modify shariff plugin settings
 */

use \PKP\components\forms\FormComponent;
use \PKP\components\forms\FieldText;
use \PKP\components\forms\FieldOptions;

define('FORM_SHARIFF_SETTINGS', 'shariffSettings');

/**
 * A form for implementing shariff settings.
 * 
 * @class ShariffSettingsForm
 * @brief Class implemnting ShariffSettingsForm
 */
class ShariffSettingsForm extends FormComponent {
	/** @copydoc FormComponent::$id */
	public $id = FORM_SHARIFF_SETTINGS;

	/** @copydoc FormComponent::$method */
	public $method = 'PUT';

	/**
	 * Constructor
	 *
	 * @param string $action string URL to submit the form to
	 * @param array $locales array Supported locales
	 * @param object $context Context Journal or Press to change settings for
	 * @param string $baseUrl string Site's base URL. Used for image previews.
	 * @param string $temporaryFileApiUrl string URL to upload files to
	 * @param string $imageUploadUrl string The API endpoint for images uploaded through the rich text field
	 * @param string $publicUrl url to the frontend page
	 * @param array $data settings for form initialization
	 */
	public function __construct($action, $locales, $context) {

		$this->action = $action;
		$this->successMessage = __('plugins.generic.shariff.settings.form.success', ['url' => $publicUrl]);
		$this->locales = $locales;

		$this->addGroup([
			'id' => 'shariffsettings',	
		], [])
		->addField(new FieldOptions('shariffServicesSelected', [
			'label' => __('plugins.generic.shariff.settings.service'),
			'tooltip' => __('plugins.generic.shariff.settings.service.shared_content.note') . "<br>" . __('plugins.generic.shariff.settings.service.note'),
			'description' => __('plugins.generic.shariff.settings.service.description'),
			'options' => [
				['value' => 'twitter', 'label' => __('plugins.generic.shariff.settings.service.twitter')],
				['value' => 'facebook', 'label' => __('plugins.generic.shariff.settings.service.facebook')],
				['value' => 'linkedin', 'label' => __('plugins.generic.shariff.settings.service.linkedin')],
				['value' => 'pinterest', 'label' => __('plugins.generic.shariff.settings.service.pinterest')],
				['value' => 'xing', 'label' => __('plugins.generic.shariff.settings.service.xing')],
				['value' => 'whatsapp', 'label' => __('plugins.generic.shariff.settings.service.whatsapp')],
				['value' => 'addthis', 'label' => __('plugins.generic.shariff.settings.service.addthis')],
				['value' => 'tumblr', 'label' => __('plugins.generic.shariff.settings.service.tumblr')],
				['value' => 'flattr', 'label' => __('plugins.generic.shariff.settings.service.flattr')],
				['value' => 'diaspora', 'label' => __('plugins.generic.shariff.settings.service.diaspora')],
				['value' => 'reddit', 'label' => __('plugins.generic.shariff.settings.service.reddit')],
				['value' => 'stumbleupon', 'label' => __('plugins.generic.shariff.settings.service.stumbleupon')],
				['value' => 'threema', 'label' => __('plugins.generic.shariff.settings.service.threema')],
				['value' => 'weibo', 'label' => __('plugins.generic.shariff.settings.service.weibo')],
				['value' => 'qzone', 'label' => __('plugins.generic.shariff.settings.service.qzone')],
				['value' => 'mail', 'label' => __('plugins.generic.shariff.settings.service.mail')],
				['value' => 'print', 'label' => __('plugins.generic.shariff.settings.service.print')],
				['value' => 'buffer', 'label' => __('plugins.generic.shariff.settings.service.buffer')],
				['value' => 'flipboard', 'label' => __('plugins.generic.shariff.settings.service.flipboard')],
				['value' => 'tencent', 'label' => __('plugins.generic.shariff.settings.service.tencentweibo')],
				['value' => 'pocket', 'label' => __('plugins.generic.shariff.settings.service.pocket')],
				['value' => 'telegram', 'label' => __('plugins.generic.shariff.settings.service.telegram')],
				['value' => 'vk', 'label' => __('plugins.generic.shariff.settings.service.vk')],
				['value' => 'info', 'label' => __('plugins.generic.shariff.settings.service.info')]
			],
			'value' => $context->getData('shariffServicesSelected') ?: [],
			'isOrderable' => true,
			'groupId' => 'shariffsettings'
		]))
		->addField(new FieldOptions('shariffThemeSelected', [
			'label' => __('plugins.generic.shariff.settings.theme'),
			'type' => 'radio',
			'options' => [
				['value' => 'standard', 'label' => __('plugins.generic.shariff.settings.theme.standard')],
				['value' => 'grey', 'label' => __('plugins.generic.shariff.settings.theme.grey')],
				['value' => 'white', 'label' => __('plugins.generic.shariff.settings.theme.white')],
			],
			'value' => $context->getData('shariffThemeSelected') ?: "standard",
			'groupId' => 'shariffsettings'
		]))
		->addField(new FieldOptions('shariffPositionSelected', [
			'label' => __('plugins.generic.shariff.settings.position'),
			'type' => 'radio',
			'options' => [
				['value' => 'submission', 'label' => __('plugins.generic.shariff.settings.position.submission')],
				['value' => 'footer', 'label' => __('plugins.generic.shariff.settings.position.footer')],
				['value' => 'sidebar', 'label' => __('plugins.generic.shariff.settings.position.sidebar')],
			],
			'value' => $context->getData('shariffPositionSelected') ?: "submission",
			'groupId' => 'shariffsettings'
		]))
		->addField(new FieldOptions('shariffOrientationSelected', [
			'label' => __('plugins.generic.shariff.settings.orientation'),
			'type' => 'radio',
			'options' => [
				['value' => 'horizontal', 'label' => __('plugins.generic.shariff.settings.orientation.horizontal')],
				['value' => 'vertical', 'label' => __('plugins.generic.shariff.settings.orientation.vertical')],
			],
			'value' => $context->getData('shariffOrientationSelected') ?: "horizontal",
			'groupId' => 'shariffsettings'
		]));
	}
}
?>
