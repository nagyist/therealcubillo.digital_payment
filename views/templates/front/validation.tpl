{capture name=path}
  <a href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html':'UTF-8'}"
    title="{l s='Go back to the Checkout' mod='vposintegration'}">{l s='Pago' mod='vposintegration'}
  </a><span class="navigation-pipe">{$navigationPipe}</span>{l s='Validacion de la compra' mod='vposintegration'}
{/capture}

<div class="vpos-validation">
  <h2>{l s='Validacion' mod='vposintegration'}</h2>

  {assign var='current_step' value='payment'}
  {include file="$tpl_dir./order-steps.tpl"}

  <h4 style="text-align:center;margin-bottom:50px">
    {$message}
  </h4>

</div>

{if $result eq 'error'}
  <p class="cart_navigation clearfix" id="cart_navigation">
    <a href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html'}">
      <button type="submit" class="button btn btn-default button-medium">
        <span>Reintentar<i class="icon-chevron-right right"></i></span>
      </button>
    </a>
  </p>
{/if}

{if $result eq 'success'}
  <p class="cart_navigation clearfix" id="cart_navigation">
    <a href="{$redirect_link}">
      <button type="submit" class="button btn btn-default button-medium">
        <span>Continuar<i class="icon-chevron-right right"></i></span>
      </button>
    </a>
  </p>
{/if}
