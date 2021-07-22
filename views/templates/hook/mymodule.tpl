{* commentaire *}

<div id="mymodule" class="mymodule">
    <h2>{l s='Mon module' d='Modules.Mymodule'}</h2>
    <div class="mymodule__content">
        {if isset($mymodule_name) && $mymodule_name}
            {$mymodule_name}
        {else}
            <i>Value not set</i>
        {/if}
    </div>
</div>