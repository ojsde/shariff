{**
 * templates/shariffBlock.tpl
 *
 * Copyright (c) 2023 Universitätsbibliothek Freie Universität Berlin
 * Distributed under the GNU GPL v3. For full terms see the file LICENSE.
 *
 * Shariff social media buttons block.
 *}

{if $dataServicesString != ""}
	{if $shariffPostionSelected == 'footer' }
		<div class="pkp_structure_footer_wrapper">
			<div class="pkp_structure_footer">
				{if $showBlockHeading}
					<section class="sub_item">
						<h2 class="label">{translate key="plugins.generic.shariff.share"}</h2>
					</section>
				{/if}
				{include file="../../../plugins/generic/shariff/templates/shariffButtons.tpl" requestedUrl=$requestedUrl backendUrl=$backendUrl locale=$locale}
			</div>
		</div>
	{elseif $shariffPostionSelected == 'submission' }
		<div class="item shariffblock">
			<div>
				{if $showBlockHeading}
					<h3 class="label">{translate key="plugins.generic.shariff.share"}</h3>
				{/if}
				{include file="../../../plugins/generic/shariff/templates/shariffButtons.tpl" requestedUrl=$requestedUrl backendUrl=$backendUrl locale=$locale}
			</div>
		</div>
	{/if}
{/if}