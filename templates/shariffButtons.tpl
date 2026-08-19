<div class="content">
    <div class="shariff plugins_generic_shariff" data-lang="{$iso1Lang|escape}"
        data-services="[{$dataServicesString|escape}]"
        data-mail-url="mailto:"
        data-mail-body="{$requestedUrl|escape}"
		{if $backendUrl}data-backend-url="{$backendUrl|escape}"{/if}
        data-theme="{$selectedTheme|escape}"
        data-orientation="{$selectedOrientation|escape}"
        data-url="{$requestedUrl|escape}">
    </div>
</div>
