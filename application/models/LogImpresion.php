<?php
class LogImpresion extends CI_Model 
{
	
	function saverecords($data, $reimpresor)
	{

        $info = array();

        $info["employee"] = $data["employee"];
        $info["sale_id_num"] = $data["sale_id_num"];
        $info["cash_total"] = $data["cash_total"];
        $info["transaction_time"] = $data["transaction_time"];
        $info["payments"] = json_encode($data["payments"]);
        $info["reimpresor"] = $reimpresor;
        // var_dump($info);die();

        $this->db->insert('ospos_logImpresion',$info);
        return true;
	}
	
}