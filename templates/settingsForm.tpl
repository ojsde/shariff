{**
 * plugins/generic/shariff/templates/settingsForm.tpl
 *
 * Copyright (c) 2018 Free University Berlin
 * Distributed under the GNU GPL v3. For full terms see the file LICENSE.
 *
 * Shariff plugin settings form template
 *
 *}

 <tab id="shariffPlugin" label="{translate key="plugins.generic.shariff.displayName"}">
	<pkp-form
		v-bind="components.{$smarty.const.FORM_SHARIFF_SETTINGS}"
		@set="set"
	/>
</tab>