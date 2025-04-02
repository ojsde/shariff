{**
 * templates/shariffBlock.tpl
 *
 * Copyright (c) 2023 Universitätsbibliothek Freie Universität Berlin
 * Distributed under the GNU GPL v3. For full terms see the file LICENSE.
 *
 * Shariff social media buttons block.
 *}

{if $dataServicesString != ""}
	<link rel="stylesheet" type="text/css" href="{$shariffCssUrl|escape}">
	<link rel="stylesheet" type="text/css" href="{$cssUrl|escape}">
	{if $enableWCAG}
		<link rel="stylesheet" type="text/css" href="{$wcagCssUrl|escape}">
	{/if}
	<div class="pkp_block block_shariff">
		{if $showBlockHeading}
			<h2 class="title">{translate key="plugins.generic.shariff.share"}</h2>
		{/if}
		<div class="content">
			<div class="shariff plugins_generic_shariff" data-lang="{$iso1Lang|escape}"
				data-services="[{$dataServicesString|escape}]"
				data-mail-url="mailto:"
				data-mail-body="{$requestedUrl|escape}"
				data-backend-url="{$backendUrl|escape}"
				data-theme="{$selectedTheme|escape}"
				data-orientation="{$selectedOrientation|escape}"
				data-url="{$requestedUrl|escape}">
			</div>
		</div>
	</div>
	<script src="{$jsUrl|escape}"></script>
{/if}
