<?php
/**
 * Created by PhpStorm.
 * User: geanGin
 * Date: 5/23/16
 * Time: 4:40 PM
 */

$app->group('/employee',function(){

    $this->get('/',function(){
        return "This is employee API with:".generateRandomUserID();
    });

    $this->post('/createNew',function($req, $res, $args){
        $input = $req->getParams();

        $joinDate = $input['joinDate'];
        $employeeId = generateEmployeeId($this->dbcon, $joinDate);
        $employeeName = $input['employeeName'];

        try{
            $sql = "insert into bEmployee (employeeId, employeeName, employeeJoinDate) value (?,?,?)";
            $stm = $this->dbcon->prepare($sql);
            $stm->bindParam(1,$employeeId, PDO::PARAM_STR);
            $stm->bindParam(2,$employeeName, PDO::PARAM_STR);
            $stm->bindParam(3,$joinDate, PDO::PARAM_STR);
            $result = returnCreationState($stm);
        }catch (PDOException $e){
            $result = array('status'=>500,'message'=>'error : '.$e->getMessage());
        }
        $result = json_encode($result);
        return $result;
    });

});