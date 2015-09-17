{**
 * plugins/generic/shariff/templates/settingsForm.tpl
 *
 * Author: Božana Bokan, Center for Digital Systems (CeDiS), Freie Universität Berlin
 * Last update: September 17, 2015
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * The settings form for the Shariff plugin.
 *}
{strip}
{assign var="pageTitle" value="plugins.generic.shariff.settings.title"}
{include file="common/header.tpl"}
{/strip}
<script type="text/javascript">
{literal}
$(document).ready(function() { setupTableDND("#servicesTable", ''); });
{/literal}
</script>

<div id="shariffSettings">
<div id="description">{translate key="plugins.generic.shariff.settings.description"}</div>
<div class="separator"></div>
<br />
<form method="post" action="{plugin_url path="settings"}">
{include file="common/formErrors.tpl"}

<table width="100%" class="listing" id="servicesTable">
{foreach from=$services item=service name=services}
	{foreach from=$service key=id item=title}
	<tr valign="top" id="formelt-{$id|escape}" class="data">
		<input type="hidden" name="services[][{$id|escape}]" value="{$title|escape}" />
		<td width="20%" class="label">{if $smarty.foreach.services.index == 0}{fieldLabel name="selectedServices" required="true" key="plugins.generic.shariff.settings.services"}{/if}</td>
		<td class="drag"><input type="checkbox" name="selectedServices[]" value="{$id|escape}"{if in_array($id, $selectedServices)} checked="checked"{/if}}/> {translate key=$title|escape}</td>
	</tr>
	{/foreach}
{/foreach}
	<tr>
		<td colspan="2"><br/></td>
	</tr>
</table>
<table width="100%" class="data">
	<tr valign="top">
		<td width="20%" class="label">{fieldLabel name="selectedTheme" key="plugins.generic.shariff.settings.theme"}</td>
		<td width="80%" class="value">
			<select name="selectedTheme" class="selectMenu" size="1">{html_options_translate options=$themes selected=$selectedTheme}</select>
		</td>
	</tr>
	<tr>
		<td colspan="2"><br/></td>
	</tr>
	<tr valign="top">
		<td width="20%" class="label">{fieldLabel name="selectedOrientation" key="plugins.generic.shariff.settings.orientation"}</td>
		<td width="80%" class="value">
			<select name="selectedOrientation" class="selectMenu" size="1">{html_options_translate options=$orientations selected=$selectedOrientation}</select>
		</td>
	</tr>
	<tr>
		<td colspan="2"><br/></td>
	</tr>
	<tr valign="top">
		<td width="20%" class="label">{fieldLabel name="position" required="true" key="plugins.generic.shariff.settings.buttonsPosition"}</td>
		<td width="80%" class="value">
			<input type="radio" name="position" id="position-{$smarty.const.SHARIFF_POSITION_ARTICLEFOOTER}" value="{$smarty.const.SHARIFF_POSITION_ARTICLEFOOTER}" {if $position eq $smarty.const.SHARIFF_POSITION_ARTICLEFOOTER} checked="checked"{/if}/> {fieldLabel name="position-`$smarty.const.SHARIFF_POSITION_ARTICLEFOOTER`" key="plugins.generic.shariff.settings.buttonsPosition.articleFooter"}<br />
			<input type="radio" name="position" id="position-{$smarty.const.SHARIFF_POSITION_ALLFOOTER}" value="{$smarty.const.SHARIFF_POSITION_ALLFOOTER}" {if $position eq $smarty.const.SHARIFF_POSITION_ALLFOOTER}checked="checked"{/if}/> {fieldLabel name="position-`$smarty.const.SHARIFF_POSITION_ALLFOOTER`" key="plugins.generic.shariff.settings.buttonsPosition.allFooter"}<br />
			<input type="radio" name="position" id="position-{$smarty.const.SHARIFF_POSITION_BLOCK}" value="{$smarty.const.SHARIFF_POSITION_BLOCK}" {if $position eq $smarty.const.SHARIFF_POSITION_BLOCK}checked="checked"{/if}/> {fieldLabel name="position-`$smarty.const.SHARIFF_POSITION_BLOCK`" key="plugins.generic.shariff.settings.buttonsPosition.block"}<br />
		</td>
	</tr>
	<tr>
		<td colspan="2"><br/></td>
	</tr>
	<tr valign="top">
		<td width="20%" class="label">{fieldLabel name="backendUrl" key="plugins.generic.shariff.settings.backend"}</td>
		<td width="80%" class="value">
			<input type="text" name="backendUrl" id="backendUrl" value="{$backendUrl|escape}" size="30" maxlength="255" class="textField" />
		</td>
	</tr>
</table>

<br/>

<input type="submit" name="save" class="button defaultButton" value="{translate key="common.save"}"/><input type="button" class="button" value="{translate key="common.cancel"}" onclick="history.go(-1)"/>
</form>

<p><span class="formRequired">{translate key="common.requiredField"}</span></p>
</div>
{include file="common/footer.tpl"}


