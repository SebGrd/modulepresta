{* commentaire *}

<div id="mymodule" class="mymodule">
    <h2>{l s='Produit en promotions' d='Modules.Title'}</h2>
    <div class="mymodule__content">
        {if isset($mymodule_name) && $mymodule_name}
            {$mymodule_name}
        {else}
            <i>Pas de promotions</i>
        {/if}
    </div>
</div>