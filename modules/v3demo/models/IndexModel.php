<?php

use airymvc\app\AppModel;

class IndexModel extends AppModel {
	
	public function getOne($table, $where, $isJSON = false) {
		$pdoResult = $this->getAll($table, $where);
		$result = NULL;
		if (isset($pdoResult[0])) {
			$result = $pdoResult[0];
		}
		if ($isJSON) {
			return json_encode($result);
		}
		
    	return $result;
    }
    
    public function getRow($id, $table, $isJSON = false, $idColName = "id") {
    	$where = array ($idColName => $id);
    	$result = $this->getOne($table, $where);
    	return $result;
    }
    
    public function getId($table, $matchRows, $idColName = "id") {
    	$result = $this->getOne($table, $matchRows);
    	return $result[$idColName];
    }
    
    public function getAll($table, $andWhere = NULL, $isJSON = false) {
		return $this->getAllWithColumns(array("*"), $table, $andWhere, $isJSON);
    }
    
    public function getAllWithColumns($columns, $table, $andWhere = NULL, $isJSON = false) {
    	$selectCols = "";
    	foreach ($columns as $col) {
    		$selectCols .= "{$col}, ";
    	}
    	$selectCols = rtrim($selectCols, ", ");
    	
    	$whereStr = "";
    	if (!is_null($andWhere) || count($andWhere) != 0) {
	    	$whereStr = "WHERE ";
	    	foreach ($andWhere as $wkey => $wvalue) {
	    		$whereStr .= "{$wkey} = :{$wkey} AND";
	    	}
	    	$whereStr = rtrim($whereStr, "AND");
    	}

    	$pstatement = $this->db->prepare("SELECT {$selectCols} FROM {$table} {$whereStr}");
    	if (!is_null($andWhere)) {
    		$pstatement->execute($andWhere);
    	} else {
    		$pstatement->execute();
    	}

    	if ($isJSON) {
    		return json_encode($pstatement->fetchAll(\PDO::FETCH_ASSOC));
    	}
    	
    	return $pstatement->fetchAll();
    }
    
    public function insertRow($row, $table) {
    	$this->db->insert($row, $table)->execute();
    	return $this->db->lastInsertId();
    }
    
    public function updateRow($row, $table, $idColName = "id") {
    	$id = $row[$idColName];
    	$this->db->update($row, $table)
    			 ->where("{$idColName} = '{$id}'");
    	$result = $this->db->execute();
    	return $result;
    }
    
    public function deleteRow($row, $table, $idColName = "id") {
    	$id = $row[$idColName];
    	$result = $this->db->delete($table)
    					   ->where("{$idColName} = '{$id}'")
    					   ->execute();
    	return $result;
    }
	
}