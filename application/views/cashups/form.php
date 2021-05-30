<style>
.modal-dialog {
    width: 1002px !important;
}
</style>


<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>

<ul id="error_message_box" class="error_message_box"></ul>
<div id="myDiv">
    <div class="col-md-6">
        <h3>Efectivo</h3>
        <p>Billetes 20.000: <span id="elbillete20">0</span></p>
        <p>Billetes 10.000: <span id="elbillete10">0</span></p>
        <p>Billetes 5.000: <span id="elbillete5">0</span></p>
        <p>Billetes 2.000: <span id="elbillete2">0</span></p>
        <p>Billetes 1.000: <span id="elbillete1">0</span></p>
        <p>Monedas 500: <span id="elmoneda500">0</span></p>
        <p>Monedas 100: <span id="elmoneda100">0</span></p>
        <p>Monedas 50: <span id="elmoneda50">0</span></p>
        <p>Monedas 10: <span id="elmoneda10">0</span></p>
        <br>
        <p>=============================================</p>
        <h3>Totales</h3>
        <p>Total Efectivo: <span id="eltotal">0</span></p>
        <p>Total Tarjetas: <span id="eltotalt">0</span></p>
        <p>Total Gastos: <span id="elgastot">0</span></p>
        <br>
        <p>=============================================</p>
        <h3>Diferencias</h3>
        <p>Diferencia Efectivo: <span id="diferenciaEfectivo1">0</span></p>
        <p>Diferencia Tarjetas: <span id="diferenciaTarjeta1">0</span></p>
        <p>Diferencia total: <span id="diferenciaTotal1">0</span></p>

        <br>



    </div>
</div>
<div class="row">
    <button onclick="imprimir()">Imprimir reporte</button>
    <div style="display:none" class="col-md-6">


        <h3>Cuadratura</h3>
        <p>Diferencia Efectivo: <span id="diferenciaEfectivo"></span></p>
        <p>Diferencia Tarjetas: <span id="diferenciaTarjeta"></span></p>
        <p>Diferencia total: <span id="diferenciaTotal"></span></p>
        <br>
        <h3>Gastos</h3>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Descripcion</th>
                    <th scope="col">Tipo de Pago</th>
                    <th scope="col">Monto</th>
                </tr>
            </thead>
            <tbody>

                <?php

                $gastoTotal = 0;

                foreach ($gastos as $g) {
                    $fechaApertura = to_datetime(strtotime($cash_ups_info->open_date));
                    $fechaCierre = to_datetime(strtotime($cash_ups_info->close_date));

                    if ($g->date  > $cash_ups_info->open_date) {


                ?>

                <tr>
                    <td><?php echo $g->expense_id; ?></td>
                    <td><?php echo $g->date; ?></td>
                    <td><?php echo $g->description; ?></td>
                    <td><?php echo $g->payment_type; ?></td>
                    <td><?php
                                if ($g->payment_type == "Efectivo") {
                                    $gastoTotal  = $gastoTotal + $g->amount;
                                }

                                echo to_currency_no_money($g->amount); ?></td>



                </tr>

                <?php


                    }
                }




                //  var_dump($gastos);

                ?>



            </tbody>
        </table>

        <br>
        <h3>Ventas</h3>

        <?php

        $ventaTotal = 0;
        $ventaTotalc = 0;
        $ventaTotalo = 0;


        foreach ($ventas as $v) {
            $fechaApertura = to_datetime(strtotime($cash_ups_info->open_date));
            $fechaCierre = to_datetime(strtotime($cash_ups_info->close_date));

            if ($v->payment_time  > $cash_ups_info->open_date) {
                if ($v->payment_type == "Efectivo") {
                    $ventaTotal  = $ventaTotal + $v->payment_amount;
                } else {
                    // tarjeta debito
                    if ($v->payment_type == "Tarjeta Débito Crédito") {
                        $ventaTotalc  = $ventaTotalc + $v->payment_amount;
                    } else {
                        // Otros pagos
                        $ventaTotalo  = $ventaTotalo + $v->payment_amount;
                    }
                }
            }
        }


        ?>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Tipo de Pago</th>
                    <th scope="col">Total</th>

                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Venta Efectivo</td>
                    <td id="ventastotalesefectivo"><?php echo to_currency_no_money($ventaTotal); ?></td>
                </tr>
                <tr>
                    <td>Venta Tarjetas </td>
                    <td id="ventastotalestarjeta"><?php echo to_currency_no_money($ventaTotalc); ?></td>
                </tr>
                <tr>
                    <td>Venta Otros (Aplicaciones) </td>
                    <td><?php echo to_currency_no_money($ventaTotalo); ?></td>
                </tr>

            </tbody>
        </table>



    </div>
    <div class="col-md-6">
        <?php
        $saldo = 0;
        $saldo =  $cash_ups_info->open_amount_cash + $ventaTotal;
        $saldo = $saldo - $gastoTotal;
        ?>



        <?php echo form_open('cashups/save/' . $cash_ups_info->cashup_id, array('id' => 'cashups_edit_form', 'class' => 'form-horizontal')); ?>
        <fieldset id="item_basic_info">
            <div class="form-group form-group-sm">
                <?php echo form_label($this->lang->line('cashups_info'), 'cash_ups_info', array('class' => 'control-label col-xs-3')); ?>
                <?php echo form_label(!empty($cash_ups_info->cashup_id) ? $this->lang->line('cashups_id') . ' ' . $cash_ups_info->cashup_id : '', 'cashup_id', array('class' => 'control-label col-xs-8', 'style' => 'text-align:left')); ?>
            </div>

            <div class="form-group form-group-sm">
                <?php echo form_label($this->lang->line('cashups_open_date'), 'open_date', array('class' => 'required control-label col-xs-3')); ?>
                <div class='col-xs-6'>
                    <div class="input-group">
                        <span class="input-group-addon input-sm"><span
                                class="glyphicon glyphicon-calendar"></span></span>
                        <?php echo form_input(array(
                            'name' => 'open_date',
                            'id' => 'open_date',
                            'class' => 'form-control input-sm datepicker',
                            'value' => to_datetime(strtotime($cash_ups_info->open_date))
                        )); ?>
                    </div>
                </div>
            </div>

            <div class="form-group form-group-sm">
                <?php echo form_label($this->lang->line('cashups_open_employee'), 'open_employee', array('class' => 'control-label col-xs-3')); ?>
                <div class='col-xs-6'>
                    <?php echo form_dropdown('open_employee_id', $employees, $cash_ups_info->open_employee_id, 'id="open_employee_id" class="form-control"'); ?>
                </div>
            </div>

            <div class="form-group form-group-sm">
                <?php echo form_label($this->lang->line('cashups_open_amount_cash'), 'open_amount_cash', array('class' => 'control-label col-xs-3')); ?>
                <div class='col-xs-4'>
                    <div class="input-group input-group-sm">
                        <?php if (!currency_side()) : ?>
                        <span
                            class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
                        <?php endif; ?>
                        <?php echo form_input(array(
                            'name' => 'open_amount_cash',
                            'id' => 'open_amount_cash',
                            'class' => 'form-control input-sm',
                            'value' => to_currency_no_money($cash_ups_info->open_amount_cash)
                        )); ?>
                        <?php if (currency_side()) : ?>
                        <span
                            class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div style="display: none;" class="form-group form-group-sm">
                <label for="ventas" class="control-label col-xs-3">Ventas del turno (Efectivo)</label>
                <div class='col-xs-4'>
                    <div class="input-group input-group-sm">
                        <?php echo form_input(array(
                            'name' => 'ventas',
                            'id' => 'ventas',
                            'label' => 'Ventas del turno',
                            'class' => 'form-control input-sm',
                            'value' => to_currency_no_money($ventaTotal)
                        )); ?>
                        <input type="hidden" name="ventasEfectivo" id="ventasEfectivo"
                            value="<?php echo to_currency_no_money($ventaTotal) ?>" />
                        <span id="errorVentasEfectivo" class="help-block"></span>


                    </div>
                </div>
            </div>




            <!-- Empieza la glosa de billetes -->
            <div class="form-group form-group-sm">
                <label for="billetesMil" class="control-label col-xs-3">Billetes 1.000</label>
                <div class='col-xs-4'>
                    <div class="input-group input-group-sm">
                        <?php echo form_input(array(
                            'name' => 'billetesMil',
                            'id' => 'billetesMil',
                            'class' => 'form-control input-sm',
                            'value' => 0
                        )); ?>


                    </div>
                </div>
            </div>
            <div class="form-group form-group-sm">
                <label for="billetesDosMil" class="control-label col-xs-3">Billetes 2.000</label>
                <div class='col-xs-4'>
                    <div class="input-group input-group-sm">
                        <?php echo form_input(array(
                            'name' => 'billetesDosMil',
                            'id' => 'billetesDosMil',
                            'class' => 'form-control input-sm',
                            'value' => 0
                        )); ?>


                    </div>
                </div>
            </div>
            <div class="form-group form-group-sm">
                <label for="billetesCincoMil" class="control-label col-xs-3">Billetes 5.000</label>
                <div class='col-xs-4'>
                    <div class="input-group input-group-sm">
                        <?php echo form_input(array(
                            'name' => 'billetesCincoMil',
                            'id' => 'billetesCincoMil',
                            'class' => 'form-control input-sm',
                            'value' => 0
                        )); ?>


                    </div>
                </div>
            </div>
            <div class="form-group form-group-sm">
                <label for="billetesDiezMil" class="control-label col-xs-3">Billetes 10.000</label>
                <div class='col-xs-4'>
                    <div class="input-group input-group-sm">
                        <?php echo form_input(array(
                            'name' => 'billetesDiezMil',
                            'id' => 'billetesDiezMil',
                            'class' => 'form-control input-sm',
                            'value' => 0
                        )); ?>


                    </div>
                </div>
            </div>
            <div class="form-group form-group-sm">
                <label for="billetesVeinteMil" class="control-label col-xs-3">Billetes 20.000</label>
                <div class='col-xs-4'>
                    <div class="input-group input-group-sm">
                        <?php echo form_input(array(
                            'name' => 'billetesVeinteMil',
                            'id' => 'billetesVeinteMil',
                            'class' => 'form-control input-sm',
                            'value' => 0
                        )); ?>


                    </div>
                </div>
            </div>
            <div class="form-group form-group-sm">
                <label for="Monedas10" class="control-label col-xs-3">Monedas 10</label>
                <div class='col-xs-4'>
                    <div class="input-group input-group-sm">
                        <?php echo form_input(array(
                            'name' => 'Monedas10',
                            'id' => 'Monedas10',
                            'class' => 'form-control input-sm',
                            'value' => 0
                        )); ?>


                    </div>
                </div>
            </div>
            <div class="form-group form-group-sm">
                <label for="Monedas50" class="control-label col-xs-3">Monedas 50</label>
                <div class='col-xs-4'>
                    <div class="input-group input-group-sm">
                        <?php echo form_input(array(
                            'name' => 'Monedas50',
                            'id' => 'Monedas50',
                            'class' => 'form-control input-sm',
                            'value' => 0
                        )); ?>


                    </div>
                </div>
            </div>
            <div class="form-group form-group-sm">
                <label for="Monedas100" class="control-label col-xs-3">Monedas 100</label>
                <div class='col-xs-4'>
                    <div class="input-group input-group-sm">
                        <?php echo form_input(array(
                            'name' => 'Monedas100',
                            'id' => 'Monedas100',
                            'class' => 'form-control input-sm',
                            'value' => 0
                        )); ?>


                    </div>
                </div>
            </div>
            <div class="form-group form-group-sm">
                <label for="Monedas500" class="control-label col-xs-3">Monedas 500</label>
                <div class='col-xs-4'>
                    <div class="input-group input-group-sm">
                        <?php echo form_input(array(
                            'name' => 'Monedas500',
                            'id' => 'Monedas500',
                            'class' => 'form-control input-sm',
                            'value' => 0,
                            'onkeypress' => "handleKeyPress(event)"

                        )); ?>


                    </div>
                </div>
            </div>
            <div class="form-group form-group-sm">
                <?php echo form_label($this->lang->line('cashups_closed_amount_cash'), 'closed_amount_cash', array('class' => 'control-label col-xs-3')); ?>
                <div class='col-xs-4'>
                    <div class="input-group input-group-sm">
                        <?php if (!currency_side()) : ?>
                        <span
                            class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
                        <?php endif; ?>
                        <?php echo form_input(array(
                            'name' => 'closed_amount_cash',
                            'id' => 'closed_amount_cash',
                            'class' => 'form-control input-sm',
                            'value' => 0
                        )); ?>
                        <input type="hidden" name="efectivoFinal" id="efectivoFinal"
                            value="<?php echo to_currency_no_money($saldo) ?>" />
                        <?php if (currency_side()) : ?>
                        <span
                            class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
                        <?php endif; ?>
                    </div>
                    <span id="errorCierreEfectivoFinal" class="help-block"></span>

                </div>
            </div>
            <!-- Termina la glosa de billetes -->
            <div class="form-group form-group-sm">
                <?php echo form_label($this->lang->line('cashups_transfer_amount_cash'), 'Gastos', array('class' => 'control-label col-xs-3')); ?>
                <div class='col-xs-4'>
                    <div class="input-group input-group-sm">
                        <?php if (!currency_side()) : ?>
                        <span
                            class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
                        <?php endif; ?>


                        <?php echo form_input(array(
                            'name' => 'transfer_amount_cash',
                            'id' => 'transfer_amount_cash',
                            'class' => 'form-control input-sm',
                            'value' => to_currency_no_money($gastoTotal)
                        )); ?>

                        <?php if (currency_side()) : ?>
                        <span
                            class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="form-group form-group-sm">
                <?php echo form_label($this->lang->line('cashups_close_date'), 'close_date', array('class' => 'required control-label col-xs-3')); ?>
                <div class='col-xs-6'>
                    <div class="input-group">
                        <span class="input-group-addon input-sm"><span
                                class="glyphicon glyphicon-calendar"></span></span>
                        <?php echo form_input(array(
                            'name' => 'close_date',
                            'id' => 'close_date',
                            'class' => 'form-control input-sm datepicker',
                            'value' => to_datetime(strtotime($cash_ups_info->close_date))
                        )); ?>
                    </div>
                </div>
            </div>

            <div class="form-group form-group-sm">
                <?php echo form_label($this->lang->line('cashups_close_employee'), 'close_employee', array('class' => 'control-label col-xs-3')); ?>
                <div class='col-xs-6'>
                    <?php echo form_dropdown('close_employee_id', $employees, $cash_ups_info->close_employee_id, 'id="close_employee_id" class="form-control"'); ?>
                </div>
            </div>



            <div class="form-group form-group-sm">
                <?php echo form_label($this->lang->line('cashups_note'), 'note', array('class' => 'control-label col-xs-3')); ?>
                <div class='col-xs-6'>
                    <?php echo form_checkbox(array(
                        'name' => 'note',
                        'id' => 'note',
                        'value' => 0,
                        'checked' => ($cash_ups_info->note) ? 1 : 0
                    )); ?>
                </div>
            </div>

            <div style="display: none;" class="form-group form-group-sm">
                <?php echo form_label($this->lang->line('cashups_closed_amount_due'), 'closed_amount_due', array('class' => 'control-label col-xs-3')); ?>
                <div class='col-xs-4'>
                    <div class="input-group input-group-sm">
                        <?php if (!currency_side()) : ?>
                        <span
                            class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
                        <?php endif; ?>
                        <?php echo form_input(array(
                            'name' => 'closed_amount_due',
                            'id' => 'closed_amount_due',
                            'class' => 'form-control input-sm',
                            'value' => to_currency_no_money($cash_ups_info->closed_amount_due)
                        )); ?>
                        <?php if (currency_side()) : ?>
                        <span
                            class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="form-group form-group-sm">
                <?php echo form_label($this->lang->line('cashups_closed_amount_card'), 'closed_amount_card', array('class' => 'control-label col-xs-3')); ?>
                <div class='col-xs-4'>
                    <div class="input-group input-group-sm">
                        <?php if (!currency_side()) : ?>
                        <span
                            class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
                        <?php endif; ?>
                        <?php echo form_input(array(
                            'name' => 'closed_amount_card',
                            'id' => 'closed_amount_card',
                            'class' => 'form-control input-sm',
                            'value' => 0
                        )); ?>
                        <input type="hidden" name="ventasTarjeta" id="ventasTarjeta"
                            value="<?php echo to_currency_no_money($ventaTotalc) ?>" />

                        <?php if (currency_side()) : ?>
                        <span
                            class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
                        <?php endif; ?>

                    </div>
                    <span id="errorVentasTarjeta" class="help-block"></span>

                </div>
            </div>

            <div class="form-group form-group-sm">
                <?php echo form_label($this->lang->line('cashups_closed_amount_check'), 'closed_amount_check', array('class' => 'control-label col-xs-3')); ?>
                <div class='col-xs-4'>
                    <div class="input-group input-group-sm">
                        <?php if (!currency_side()) : ?>
                        <span
                            class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
                        <?php endif; ?>
                        <?php echo form_input(array(
                            'name' => 'closed_amount_check',
                            'id' => 'closed_amount_check',
                            'class' => 'form-control input-sm',
                            'value' => to_currency_no_money($ventaTotalo)
                        )); ?>
                        <?php if (currency_side()) : ?>
                        <span
                            class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="form-group form-group-sm">
                <?php echo form_label($this->lang->line('cashups_closed_amount_total'), 'closed_amount_total', array('class' => 'control-label col-xs-3')); ?>
                <div class='col-xs-4'>
                    <div class="input-group input-group-sm">
                        <?php if (!currency_side()) : ?>
                        <span
                            class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
                        <?php endif; ?>
                        <?php echo form_input(array(
                            'name' => 'closed_amount_total',
                            'id' => 'closed_amount_total',
                            'readonly' => 'true',
                            'class' => 'form-control input-sm',
                            'value' => to_currency_no_money($cash_ups_info->closed_amount_total)
                        )); ?>
                        <?php if (currency_side()) : ?>
                        <span
                            class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="form-group form-group-sm">
                <?php echo form_label($this->lang->line('cashups_description'), 'description', array('class' => 'control-label col-xs-3')); ?>
                <div class='col-xs-6'>
                    <?php echo form_textarea(array(
                        'name' => 'description',
                        'id' => 'description',
                        'class' => 'form-control input-sm',
                        'value' => $cash_ups_info->description
                    )); ?>
                </div>
            </div>

            <?php
            if (!empty($cash_ups_info->cashup_id)) {
            ?>
            <div class="form-group form-group-sm">
                <?php echo form_label($this->lang->line('cashups_is_deleted') . ':', 'deleted', array('class' => 'control-label col-xs-3')); ?>
                <div class='col-xs-5'>
                    <?php echo form_checkbox(array(
                            'name' => 'deleted',
                            'id' => 'deleted',
                            'value' => 1,
                            'checked' => ($cash_ups_info->deleted) ? 1 : 0
                        )); ?>
                </div>
            </div>
            <?php
            }
            ?>
        </fieldset>
        <?php echo form_close(); ?>
        <div class="bootstrap-dialog-footer-buttons">
            <button class="btn btn-primary" onclick="validar();">Validar</button>
        </div>
    </div>



</div>
<script type='text/javascript'>
function imprimir() {
    let printjs = document.createElement("script");
    printjs.src = "https://printjs-4de6.kxcdn.com/print.min.js";
    document.body.appendChild(printjs);

    printjs.onload = function() {
        printJS('myDiv', 'html');
    }

}

function handleKeyPress(e) {

    var Monedas10 = 10 * document.getElementById('Monedas10').value;
    var Monedas50 = 50 * document.getElementById('Monedas50').value;
    var Monedas100 = 100 * document.getElementById('Monedas100').value;
    var Monedas500 = 500 * document.getElementById('Monedas500').value;
    var billetesMil = 1000 * document.getElementById('billetesMil').value;
    var billetesDosMil = 2000 * document.getElementById('billetesDosMil').value;
    var billetesCincoMil = 5000 * document.getElementById('billetesCincoMil').value;
    var billetesDiezMil = 10000 * document.getElementById('billetesDiezMil').value;
    var billetesVeinteMil = 20000 * document.getElementById('billetesVeinteMil').value;


    var total = (Monedas10 + Monedas50 + Monedas100 + Monedas500 + billetesMil + billetesDosMil + billetesCincoMil +
        billetesDiezMil + billetesVeinteMil);
    console.log(total);
    document.getElementById("closed_amount_cash").innerHTML = total;
    document.getElementById("closed_amount_cash").value = total;


}


//closed_amount_cash
function replaceAll(text, busca, reemplaza) {
    while (text.toString().indexOf(busca) != -1)
        text = text.toString().replace(busca, reemplaza);
    return text;
}

function validar() {
    // Ingreso de ventas efectivo
    // valid = 0;
    // var efectivoIngresado = document.getElementById('ventas').value.replace(/./g, ''); // Esconder este campo
    // var efectivoSistema = parseInt(document.getElementById('ventasEfectivo').value.replace(/./g, '')); //250.000
    // var aperturaCaja = parseInt(document.getElementById('open_amount_cash').value.replace(/./g, '')); //100.000
    // var closed_amount_cash = parseInt(document.getElementById('closed_amount_cash').value.replace(/./g, '')); // suma de los billetes

    document.getElementById("elbillete20").innerHTML = document.getElementById('billetesVeinteMil').value;
    document.getElementById("elbillete10").innerHTML = document.getElementById('billetesDiezMil').value;
    document.getElementById("elbillete5").innerHTML = document.getElementById('billetesCincoMil').value;
    document.getElementById("elbillete2").innerHTML = document.getElementById('billetesDosMil').value;
    document.getElementById("elbillete1").innerHTML = document.getElementById('billetesMil').value;
    document.getElementById("elmoneda500").innerHTML = document.getElementById('Monedas500').value;
    document.getElementById("elmoneda100").innerHTML = document.getElementById('Monedas100').value;
    document.getElementById("elmoneda50").innerHTML = document.getElementById('Monedas50').value;
    document.getElementById("elmoneda10").innerHTML = document.getElementById('Monedas10').value;
    document.getElementById("eltotal").innerHTML = document.getElementById('closed_amount_cash').value;
    document.getElementById("eltotalt").innerHTML = document.getElementById('closed_amount_card').value;
    document.getElementById("elgastot").innerHTML = document.getElementById('transfer_amount_cash').value;

    valid = 0;
    var efectivoIngresado = replaceAll(document.getElementById('ventas').value, '.', ""); // Esconder este campo
    var efectivoSistema = replaceAll(document.getElementById('ventasEfectivo').value, '.', ""); // Esconder este campo
    var aperturaCaja = replaceAll(document.getElementById('open_amount_cash').value, '.', ""); // Esconder este campo
    var ventasEfectivo = replaceAll(document.getElementById('ventasEfectivo').value, '.', ""); // Esconder este campo
    var gastos = replaceAll(document.getElementById('transfer_amount_cash').value, '.', ""); // Esconder este campo
    var ventastotalesefectivo = replaceAll(<?php echo to_currency_no_money($ventaTotal); ?>, '.', ""); // Esconder este campo
    var closed_amount_card = replaceAll(document.getElementById('closed_amount_card').value, '.', ""); // Esconder este campo
    var ventastotalestarjeta = replaceAll(document.getElementById('ventasTarjeta').value, '.', ""); // Esconder este campo

    
    var closed_amount_cash = replaceAll(document.getElementById('closed_amount_cash').value, '.',
        ""); // Esconder este campo



    // var efectivoSistema = document.getElementById('ventasEfectivo').value; //250.000
    // var aperturaCaja = document.getElementById('open_amount_cash').value; //100.000
    // var closed_amount_cash = document.getElementById('closed_amount_cash').value; // suma de los billetes

    console.log("Efectivo Ingresado " + efectivoIngresado);
    console.log("Efectivo sistema " + efectivoSistema);
    console.log("Apertura Caja " + aperturaCaja);
    var totalSistemaEfectivo = (parseInt(aperturaCaja) + parseInt(efectivoSistema)); // 207750

    console.log("total de venta " + totalSistemaEfectivo);

    if (closed_amount_cash != totalSistemaEfectivo) {

        var totalefectivocontado =((parseInt(aperturaCaja)-parseInt(gastos))+parseInt(closed_amount_cash));
        var totalefectivosistema =((parseInt(aperturaCaja)-parseInt(gastos))+parseInt(efectivoIngresado));
        var diferenciatarjeta = parseInt(closed_amount_card) - parseInt(ventastotalestarjeta);
        var diferenciaefectivo = parseInt(totalefectivocontado) - parseInt(totalefectivosistema);

        console.log("------------------------------");
        console.log("Total Efectivo Contado: "+totalefectivocontado);
        console.log("Total Efectivo Sistema: "+totalefectivosistema);
        console.log("Diferencia Tarjeta: "+diferenciatarjeta);
        console.log("Diferencia Efectivo: "+diferenciaefectivo);


        console.log("------------------------------");
        var resultado = parseInt(closed_amount_cash) - parseInt(aperturaCaja); // 208.650 - 100.000

        console.log("La diferencia entre la apertura y la cuenta de billetes: " + resultado); // 108.650


        console.log("Esta es la venta que sale al lado izquerdo: " + ventasEfectivo);
        var result = parseInt(resultado) - parseInt(ventasEfectivo); // 108.650 - 107.750


        console.log(parseInt(result));
        document.getElementById("diferenciaEfectivo").innerHTML = diferenciaefectivo;
        document.getElementById("diferenciaEfectivo1").innerHTML = diferenciaefectivo;

        document.getElementById("errorVentasEfectivo").innerHTML = "Existen diferencias, efectivo en sistema:" +
        diferenciaefectivo;
        valid = 1;
    } else {
        document.getElementById("errorVentasEfectivo").innerHTML = "Sin Diferencias";
        valid = 0;
    }

    // Ingreso de Ventas Tarjetas

    var tarjetaIngresado = replaceAll(document.getElementById('closed_amount_card').value, '.',
        ""); // Esconder este campo
    var tarjetaSistema = replaceAll(document.getElementById('ventasTarjeta').value, '.', ""); // Esconder este campo

    if (tarjetaIngresado != tarjetaSistema) {

        var resultadot = parseInt(tarjetaIngresado) - parseInt(tarjetaSistema);

        console.log(resultadot);


        document.getElementById("diferenciaTarjeta").innerHTML = diferenciatarjeta;
        document.getElementById("diferenciaTarjeta1").innerHTML = diferenciatarjeta;

        document.getElementById("diferenciaTotal").innerHTML = Math.round(diferenciaefectivo + diferenciatarjeta, 1);
        document.getElementById("diferenciaTotal1").innerHTML = Math.round(diferenciaefectivo + diferenciatarjeta, 1);


        document.getElementById("errorVentasTarjeta").innerHTML =
            "Existen diferencias, ventas realizadas con tarjetas:" +
            diferenciatarjeta;
        valid = 1;

    } else {
        document.getElementById("errorVentasTarjeta").innerHTML = "Sin Diferencias";
        valid = 0;

    }

    //Efectivo Final closed_amount_cash

    var efectivoFinalingresado = document.getElementById('closed_amount_cash').value;
    var efectivoFinalSistema = document.getElementById('efectivoFinal').value;

    if (efectivoFinalingresado != efectivoFinalSistema) {
        document.getElementById("errorCierreEfectivoFinal").innerHTML =
            "Existen diferencias, en el efectivo final Final:" +
            efectivoFinalSistema;
        valid = 1;

    } else {
        document.getElementById("errorCierreEfectivoFinal").innerHTML = "Sin Diferencias";
        valid = 0;

    }

    document.getElementById("submit").disabled = false;

}
//validation and submit handling

$(document).ready(function() {
    document.getElementById("submit").disabled = true;

    <?php $this->load->view('partial/datepicker_locale'); ?>

    $('#open_date').datetimepicker({
        format: "<?php echo dateformat_bootstrap($this->config->item('dateformat')) . ' ' . dateformat_bootstrap($this->config->item('timeformat')); ?>",
        startDate: "<?php echo date($this->config->item('dateformat') . ' ' . $this->config->item('timeformat'), mktime(0, 0, 0, 1, 1, 2010)); ?>",
        <?php
            $t = $this->config->item('timeformat');
            $m = $t[strlen($t) - 1];
            if (strpos($this->config->item('timeformat'), 'a') !== false || strpos($this->config->item('timeformat'), 'A') !== false) {
            ?>
        showMeridian: true,
        <?php
            } else {
            ?>
        showMeridian: false,
        <?php
            }
            ?>
        minuteStep: 1,
        autoclose: true,
        todayBtn: true,
        todayHighlight: true,
        bootcssVer: 3,
        language: '<?php echo current_language_code(); ?>'
    });

    $('#close_date').datetimepicker({
        format: "<?php echo dateformat_bootstrap($this->config->item('dateformat')) . ' ' . dateformat_bootstrap($this->config->item('timeformat')); ?>",
        startDate: "<?php echo date($this->config->item('dateformat') . ' ' . $this->config->item('timeformat'), mktime(0, 0, 0, 1, 1, 2010)); ?>",
        <?php
            $t = $this->config->item('timeformat');
            $m = $t[strlen($t) - 1];
            if (strpos($this->config->item('timeformat'), 'a') !== false || strpos($this->config->item('timeformat'), 'A') !== false) {
            ?>
        showMeridian: true,
        <?php
            } else {
            ?>
        showMeridian: false,
        <?php
            }
            ?>
        minuteStep: 1,
        autoclose: true,
        todayBtn: true,
        todayHighlight: true,
        bootcssVer: 3,
        language: '<?php echo current_language_code(); ?>'
    });

    $('#open_amount_cash, #transfer_amount_cash, #closed_amount_cash, #closed_amount_due, #closed_amount_card, #closed_amount_check')
        .keyup(function() {
            $.post("<?php echo site_url($controller_name . '/ajax_cashup_total') ?>", {
                    'open_amount_cash': $('#open_amount_cash').val(),
                    'transfer_amount_cash': $('#transfer_amount_cash').val(),
                    'closed_amount_due': $('#closed_amount_due').val(),
                    'closed_amount_cash': $('#closed_amount_cash').val(),
                    'closed_amount_card': $('#closed_amount_card').val(),
                    'closed_amount_check': $('#closed_amount_check').val()
                },
                function(response) {
                    $('#closed_amount_total').val(response.total);
                },
                'json'
            );
        });

    var submit_form = function() {
        $(this).ajaxSubmit({
            success: function(response) {
                dialog_support.hide();
                table_support.handle_submit('<?php echo site_url('cashups'); ?>', response);
            },
            dataType: 'json'
        });
    };

    $('#cashups_edit_form').validate($.extend({
        submitHandler: function(form) {
            submit_form.call(form);
        },
        rules: {

        },
        messages: {
            open_date: {
                required: '<?php echo $this->lang->line('cashups_date_required'); ?>'

            },
            close_date: {
                required: '<?php echo $this->lang->line('cashups_date_required'); ?>'

            },
            amount: {
                required: '<?php echo $this->lang->line('cashups_amount_required'); ?>',
                number: '<?php echo $this->lang->line('cashups_amount_number'); ?>'
            }
        }
    }, form_support.error));
});
</script>