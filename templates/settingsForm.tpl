{**
 * plugins/generic/shariff/templates/settingsForm.tpl
 *
 * Copyright (c) 2018 Free University Berlin
 * Distributed under the GNU GPL v2. For full terms see the file LICENSE.
 *
 * Shariff plugin settings
 *
 *}
<script>
	$(function() {ldelim}
		// Attach the form handler.
		$('#shariffSettingsForm').pkpHandler('$.pkp.controllers.form.AjaxFormHandler');
	{rdelim});
</script>

<form class="pkp_form" id="shariffSettingsForm" method="post" action="{url router=$smarty.const.ROUTE_COMPONENT op="manage" category="generic" plugin=$pluginName verb="settings" save=true}">

	{csrf}

	{include file="controllers/notification/inPlaceNotification.tpl" notificationId="shariffSettingsFormNotification"}

	{fbvFormArea id="shariffSettingsFormArea"}

		{* Choose social media services *}
		{fbvFormSection list="true" label="plugins.generic.shariff.settings.service"}
			{foreach from=$services item=service name=services}
				{foreach from=$service key=id item=title}
					{assign var=checked value=false}
					{if $selectedServices && $id|in_array:$selectedServices}
						{assign var=checked value=true}
					{/if}
					{fbvElement type="checkbox" name="selectedServices[]" id=$id value=$id label=$title checked=$checked inline=true}
				{/foreach}
			{/foreach}
		{/fbvFormSection}

		{* Choose theme *}
		{fbvFormSection label="plugins.generic.shariff.settings.theme"}
			{fbvElement type="select" id="selectedTheme" from=$themes selected=$selectedTheme size=$fbvStyles.size.MEDIUM}
		{/fbvFormSection}

		{* Choose position *}
		{fbvFormSection label="plugins.generic.shariff.settings.position"}
			{fbvElement type="select" id="selectedPosition" from=$positions  selected=$selectedPosition size=$fbvStyles.size.MEDIUM}
		{/fbvFormSection}

		{* Choose orientation *}
		{fbvFormSection label="plugins.generic.shariff.settings.orientation"}
			{fbvElement type="select" id="selectedOrientation" from=$orientations  selected=$selectedOrientation size=$fbvStyles.size.MEDIUM}
		{/fbvFormSection}

	{/fbvFormArea}

	{translate key="plugins.generic.shariff.settings.note"}

	{fbvFormButtons}
	<p><span class="formRequired">{translate key="common.requiredField"}</span></p>
</form>
