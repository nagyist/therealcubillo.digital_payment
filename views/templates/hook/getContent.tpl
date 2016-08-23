<fieldset>
  {if isset($confirmation)}
    <div class="alert alert-success">
      Settings updated
    </div>
  {/if}
  <h2>VPOS Configuracion</h2>
  <div class="panel">
    <div class="panel-heading">
      <legend>
        Configuracion
      </legend>
    </div>
    <form class="" method="post">
      <div class="form-group clearfix">
        <label class="col-lg-3">Configuracion del ambiente</label>
        <div class="col-lg-9">
          <select name="environment">
            <option value="0" {if $environment eq '0'}selected{/if} >Debug</option>
            <option value="1" {if $environment eq '1'}selected{/if} >Produccion</option>
          </select>
        </div>
      </div>
      <div class="form-group clearfix">
        <label class="col-lg-3">ID Adquirente</label>
        <div class="col-lg-9">
          <input type="text" name="id_adquirer" value="{$id_adquirer}">
        </div>
      </div>
      <div class="form-group clearfix">
        <label class="col-lg-3">ID Comercio</label>
        <div class="col-lg-9">
          <input type="text" name="id_commerce" value="{$id_commerce}">
        </div>
      </div>
      <div class="form-group clearfix">
        <label class="col-lg-3">Codigo Terminal</label>
        <div class="col-lg-9">
          <input type="text" name="terminal_code" value="{$terminal_code}">
        </div>
      </div>
      <div class="form-group clearfix">
        <label class="col-lg-3">ID Mall</label>
        <div class="col-lg-9">
          <input type="text" name="id_mall" value="{$id_mall}">
        </div>
      </div>
      <div class="form-group clearfix">
        <label class="col-lg-3">Vector de Inicializacion</label>
        <div class="col-lg-9">
          <input type="text" name="vector" value="{$vector}">
        </div>
      </div>
      <div class="form-group clearfix">
        <label class="col-lg-3">Llave Privada Firma</label>
        <div class="col-lg-9">
          <textarea name="sign_priv_key">{$sign_priv_key}</textarea>
        </div>
      </div>
      <div class="form-group clearfix">
        <label class="col-lg-3">Llave Publica Firma</label>
        <div class="col-lg-9">
          <textarea name="sign_pub_key">{$sign_pub_key}</textarea>
        </div>
      </div>
      <div class="form-group clearfix">
        <label class="col-lg-3">Llave Privada Encriptacion</label>
        <div class="col-lg-9">
          <textarea name="enc_priv_key">{$enc_priv_key}</textarea>
        </div>
      </div>
      <div class="form-group clearfix">
        <label class="col-lg-3">Llave Publica Encriptacion</label>
        <div class="col-lg-9">
          <textarea name="enc_pub_key">{$enc_pub_key}</textarea>
        </div>
      </div>
      <div class="form-group clearfix">
        <label class="col-lg-3">Llave Publica Alignet Encriptacion</label>
        <div class="col-lg-9">
          <textarea name="enc_ali_key">{$enc_ali_key}</textarea>
        </div>
      </div>
      <div class="form-group clearfix">
        <label class="col-lg-3">Llave Publica Alignet Firma</label>
        <div class="col-lg-9">
          <textarea name="sign_ali_key">{$sign_ali_key}</textarea>
        </div>
      </div>
      <div class="panel-footer">
        <input class="btn btn-default pull-right" type="submit" name="vpos_pago" value="Guardar">
      </div>
    </form>
  </div>
</fieldset>
