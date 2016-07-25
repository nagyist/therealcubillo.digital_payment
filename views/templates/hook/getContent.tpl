<fieldset>
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
          <select name="ENVIRONMENT">
            <option value="0" >Debug</option>
            <option value="1" >Produccion</option>
          </select>
        </div>
      </div>
      <div class="form-group clearfix">
        <label class="col-lg-3">ID Adquirente</label>
        <div class="col-lg-9">
          <input type="text" name="id_adquirer" value="">
        </div>
      </div>
      <div class="form-group clearfix">
        <label class="col-lg-3">ID Comercio</label>
        <div class="col-lg-9">
          <input type="text" name="ID_COMMERCE" value="">
        </div>
      </div>
      <div class="form-group clearfix">
        <label class="col-lg-3">Codigo Terminal</label>
        <div class="col-lg-9">
          <input type="text" name="TERMINAL_CODE" value="">
        </div>
      </div>
      <div class="form-group clearfix">
        <label class="col-lg-3">ID Mall</label>
        <div class="col-lg-9">
          <input type="text" name="ID_MALL" value="">
        </div>
      </div>
      <div class="form-group clearfix">
        <label class="col-lg-3">Vector de Inicializacion</label>
        <div class="col-lg-9">
          <input type="text" name="VECTOR" value="">
        </div>
      </div>
      <div class="form-group clearfix">
        <label class="col-lg-3">Llave Privada Firma</label>
        <div class="col-lg-9">
          <textarea name="SIGN_PRIV_KEY"></textarea>
        </div>
      </div>
      <div class="form-group clearfix">
        <label class="col-lg-3">Llave Publica Firma</label>
        <div class="col-lg-9">
          <textarea name="SIGN_PUB_KEY"></textarea>
        </div>
      </div>
      <div class="form-group clearfix">
        <label class="col-lg-3">Llave Privada Encriptacion</label>
        <div class="col-lg-9">
          <textarea name="ENC_PRIV_KEY"></textarea>
        </div>
      </div>
      <div class="form-group clearfix">
        <label class="col-lg-3">Llave Publica Encriptacion</label>
        <div class="col-lg-9">
          <textarea name="ENC_PUB_KEY"></textarea>
        </div>
      </div>
      <div class="panel-footer">
        <input class="btn btn-default pull-right" type="submit" name="vpos_pago" value="Guardar">
      </div>
    </form>
  </div>
</fieldset>
