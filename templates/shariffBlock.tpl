{**
 * templates/shariffBlock.tpl
 *
 * Copyright (c) 2023 Universitätsbibliothek Freie Universität Berlin
 * Distributed under the GNU GPL v3. For full terms see the file LICENSE.
 *
 * Shariff social media buttons block.
 *}
{if $dataServicesString != ''}
	<link rel="stylesheet" type="text/css" href="{$cssUrl|escape}">
	<div class="shariff pkp_block plugins_generic_shariff" data-lang="{$iso1Lang|escape}"
		data-services="[{$dataServicesString|escape}]"
		data-mail-url="mailto:"
		data-mail-body={url}
		data-backend-url="{$backendUrl|escape}"
		data-theme="{$selectedTheme|escape}"
		data-orientation="{$selectedOrientation|escape}"
		data-url="{$requestedUrl|escape}">
	</div>
	<script src="{$jsUrl|escape}"></script>
{/if}
