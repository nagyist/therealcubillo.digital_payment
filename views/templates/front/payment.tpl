{capture name=path}
  {l s='VPOS Pagos' mod='vposintegration'}
{/capture}

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

<form action="https://integracion.alignetsac.com/VPOS/MM/transactionStart20.do" target="_blank" class="" method="post">
  <input type="submit" name="name" value="Comprar">
</form>
