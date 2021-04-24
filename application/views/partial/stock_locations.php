<?php

$data['dte']['hash'] = $this->config->item('hash');
$data['dte']['url_dte'] = $this->config->item('url_dte');

$url = $data['dte']['url_dte'];

// incluir autocarga de composer
require('../vendor/autoload.php');
$str = $data['dte']['hash'] . ':x';
// crear cliente
$LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($str, $url);
// $LibreDTE->setSSL(false, false); ///< segundo parámetro =false desactiva verificación de SSL

// crear DTE temporal
$datos = $LibreDTE->get('/dte/contribuyentes/info/' . $this->config->item('rutdte'));
// var_dump($datos["body"]["sucursales"]);
// die();
$i = 0;
$options = $datos["body"]["sucursales"];

foreach ($stock_locations as $location => $location_data) {
	$location_id = $location_data['location_id'];
	$location_name = $location_data['location_name'];
	$location_CdgSIISucur = $location_data['CdgSIISucur'];

	++$i;
?>
<div class="form-group form-group-sm"
    style="<?php echo $location_data['deleted'] ? 'display:none;' : 'display:block;' ?>">
    <?php echo form_label($this->lang->line('nexo6445') . ' ' . $i, 'stock_location_' . $i, array('class' => 'required control-label col-xs-2')); ?>
    <div class='col-xs-2'>
        <?php $form_data = array(
				'name' => "stock_location[$location_id]",
				'id' => "stock_location[$location_id]",
				'class' => 'stock_location valid_chars form-control input-sm required',
				'value' => $location_name
			);
			$location_data['deleted'] && $form_data['disabled'] = 'disabled';
			echo form_input($form_data);
			?>
    </div>
    <div class="col-xs-8">
        <?php
			echo form_dropdown('CdgSIISucur' . "[" . $location_id . "]", $options, $location_CdgSIISucur);

			?></div>
    <span class="add_stock_location glyphicon glyphicon-plus" style="padding-top: 0.5em;"></span>
    <span>&nbsp;&nbsp;</span>
    <span class="remove_stock_location glyphicon glyphicon-minus" style="padding-top: 0.5em;"></span>
</div>
<?php
}
?>