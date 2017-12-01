{**
 * templates/shariffBlock.tpl
 *
 * Author: Božana Bokan, Center for Digital Systems (CeDiS), Freie Universität Berlin
 * Last update: October 22, 2015
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Shariff social media buttons block.
 *}
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
