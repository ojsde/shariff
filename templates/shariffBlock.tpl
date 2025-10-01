{**
 * templates/shariffBlock.tpl
 *
 * Copyright (c) 2023 Universitätsbibliothek Freie Universität Berlin
 * Distributed under the GNU GPL v3. For full terms see the file LICENSE.
 *
 * Shariff social media buttons block.
 *}

{if $dataServicesString != "" or $sidebarBlock }
	<div class="pkp_block block_shariff">
		{if $showBlockHeading}
			<h2 class="title">{translate key="plugins.generic.shariff.share"}</h2>
		{/if}
		{include file="../../../plugins/generic/shariff/templates/shariffButtons.tpl" requestedUrl=$requestedUrl backendUrl=$backendUrl locale=$locale}
	</div>
{/if}
