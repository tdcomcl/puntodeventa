<style>
.modal-dialog {
    width: 1002px !important;
}
</style>


<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>

<ul id="error_message_box" class="error_message_box"></ul>

<div class="row">
    <div class="col-md-6">

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


        foreach ($ventas as $v) {
            $fechaApertura = to_datetime(strtotime($cash_ups_info->open_date));
            $fechaCierre = to_datetime(strtotime($cash_ups_info->close_date));

            if ($v->payment_time  > $cash_ups_info->open_date) {
                if ($v->payment_type == "Efectivo") {
                    $ventaTotal  = $ventaTotal + $v->payment_amount;
                } else {
                    $ventaTotalc  = $ventaTotalc + $v->payment_amount;
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
                    <td><?php echo to_currency_no_money($ventaTotal); ?></td>
                </tr>
                <tr>
                    <td>Venta Tarjetas Otros</td>
                    <td><?php echo to_currency_no_money($ventaTotalc); ?></td>
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
            <div class="form-group form-group-sm">
                <label for="ventas" class="control-label col-xs-3">Ventas del turno (Efectivo)</label>
                <div class='col-xs-4'>
                    <div class="input-group input-group-sm">
                        <?php echo form_input(array(
                            'name' => 'ventas',
                            'id' => 'ventas',
                            'label' => 'Ventas del turno',
                            'class' => 'form-control input-sm',
                            'value' => 0
                        )); ?>
                        <input type="hidden" name="ventasEfectivo" id="ventasEfectivo"
                            value="<?php echo to_currency_no_money($ventaTotal) ?>" />
                        <span id="errorVentasEfectivo" class="help-block"></span>
                    </div>
                </div>
            </div>

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

            <div class="form-group form-group-sm">
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
                            'value' => to_currency_no_money($cash_ups_info->closed_amount_check)
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
function validar() {
    // Ingreso de ventas efectivo
    valid = 0;
    var efectivoIngresado = document.getElementById('ventas').value;
    var efectivoSistema = document.getElementById('ventasEfectivo').value;

    if (efectivoIngresado != efectivoSistema) {
        document.getElementById("errorVentasEfectivo").innerHTML = "Existen diferencias, efectivo en sistema:" +
            efectivoSistema;
        valid = 1;
    } else {
        document.getElementById("errorVentasEfectivo").innerHTML = "Sin Diferencias";
        valid = 0;
    }

    // Ingreso de Ventas Tarjetas
    var tarjetaIngresado = document.getElementById('closed_amount_card').value;
    var tarjetaSistema = document.getElementById('ventasTarjeta').value;

    if (tarjetaIngresado != tarjetaSistema) {
        document.getElementById("errorVentasTarjeta").innerHTML =
            "Existen diferencias, ventas realizadas con tarjetas:" +
            tarjetaSistema;
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

    if (valid == 0) {
        document.getElementById("submit").disabled = false;

    }

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