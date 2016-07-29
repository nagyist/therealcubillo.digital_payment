{capture name=path}
  <a href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html':'UTF-8'}"
    title="{l s='Go back to the Checkout' mod='vposintegration'}">{l s='Checkout' mod='vposintegration'}
  </a><span class="navigation-pipe">{$navigationPipe}</span>{l s='Check payment' mod='vposintegration'}
{/capture}

<h2>{l s='Order summary' mod='vposintegration'}</h2>

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}



<!-- https://integracion.alignetsac.com/VPOS/MM/transactionStart20.do -->
<form action="{$link->getModuleLink('vposintegration', 'validation', [], true)|escape:'html'}" target="_blank" class="" method="post">
  <div class="box">

    <p>
      Usted tiene {$nb_products} productos en el carrito
    </p>

    <table>
      <tr>
        <td>
          Cantidad
        </td>
        <td>
          Producto
        </td>
      </tr>
      {foreach $products as $product}
      <tr>
        <td>
          {$product.cart_quantity}
        </td>
        <td>
          {$product.name}
        </td>
      </tr>
      {/foreach}
    </table>

    <p>
      El total de su compra es {$total_amount}
    </p>

    <input type="hidden" name="IDACQUIRER" value="{$id_adquirer}">
    <input type="hidden" name="IDCOMMERCE" value="{$id_commerce}">
    <input type="hidden" name="XMLREQ" value="{$xmlreq}">
    <input type="hidden" name="DIGITALSIGN" value="{$digitalsign}">
    <input type="hidden" name="SESSIONKEY" value="{$sessionkey}">

    <p>
       <b>{l s='Al presionar \'Confirmo mi pedido\' se abrira una nueva ventana, es necesario que ingrese los datos que se le indican.' mod='vposintegration'}</b>
    </p>
  </div>
  <p class="cart_navigation clearfix" id="cart_navigation">
    <button type="submit" class="button btn btn-default button-medium">
      <span>Confirmo mi pedido<i class="icon-chevron-right right"></i></span>
    </button>
    <a href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html'}" class="button-exclusive btn btn-default"><i class="icon-chevron-left"></i>{l s='Volver' mod='vposintegration'}</a>
  </p>
</form>
