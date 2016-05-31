<?php
/**
 * Created by PhpStorm.
 * User: geanGin
 * Date: 5/19/16
 * Time: 2:40 PM
 */

function generateRandomUserID(){
    return uniqid();
};

function generateProjectId($projName){
    $prefix = uniqid();
    $today = date("Ymd");
    $prefixProjName = substr($projName,0,2);
    $projId = $prefixProjName.$prefix.$today;
    return $projId;
}

function generateEmployeeId($dbcon, $joinDate){
    try{
        $sql="select count(*) as counter from bEmployee where employeeJoinDate = ?";
        $stm = $dbcon->prepare($sql);
        $stm->bindParam(1, $joinDate, PDO::PARAM_STR);
        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);

        // Reformat
        $joinDate = date_create_from_format('Y-m-d',$joinDate);
        $joinDate = date_format($joinDate, 'Ymd');
        $counter = sprintf("%03d",$result[0]['counter']+1);
        $idResult = $counter.$joinDate;
    }catch (PDOException $e){
        $idResult = -99;
    }

    return $idResult;
}

function returnCreationState($stm){
    if($stm->execute()){
        $result = array("status"=>201,"message"=>"item created");
    }else{
        $result = array("status"=>202,"message"=>"query execution returned unsuccessful");
    }
    return $result;
}

function returnUpdateState($stm){
    if($stm->execute()){
        $result = array("status"=>200,"message"=>"item info updated");
    }else{
        $result = array("status"=>202,"message"=>"query execution returned unsuccessful");
    }
    return $result;
}

function returnDataSingle($row, $data){
    if($row>0){
        $result = array("status"=>200,"message"=>"returned with data","data"=>$data[0]);
    }else{
        $result = array("status"=>204,"message"=>"query returned with no data");
    }
    return $result;
}

function returnDataCollection($row, $data){
    if($row>0){
        $result = array("status"=>200,"message"=>"returned with data","data"=>$data);
    }else{
        $result = array("status"=>204,"message"=>"query returned with no data");
    }
    return $result;
}