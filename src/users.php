<?php
/**
 * Created by PhpStorm.
 * User: geanGin
 * Date: 5/23/16
 * Time: 4:39 PM
 */

$app->group('/users',function(){

    $this->get('/',function($req, $res, $args){
        return "This is users API with:".generateRandomUserID();
    });

    $this->post('/createNew',function($req, $res, $args){
        $input = $req->getParams();

        $userName = $input['userName'];
        $userPass = $input['userPass'];
        $userEmployeeId = $input['employeeId'];
        $userId = generateRandomUserID();

        try{
            $sql = "insert into bUser (userId, userPass, employeeId, userName) value (?,md5(?),?,?)";
            $stm = $this->dbcon->prepare($sql);
            $stm->bindParam(1,$userId,PDO::PARAM_STR);
            $stm->bindParam(2,$userPass,PDO::PARAM_STR);
            $stm->bindParam(3,$userEmployeeId,PDO::PARAM_STR);
            $stm->bindParam(4,$userName,PDO::PARAM_STR);
            $result = returnCreationState($stm);
        }catch (PDOException $e){
            $result = array('status'=>500,'message'=>'internal server error :'.$e->getMessage());
        }
        $result = json_encode($result);
        return $result;
    });

    $this->post('/signIn',function($request, $response, $args){
        $input = $request->getParams();

        $userName = $input['userName'];
        $userPass = $input['userPass'];

        try{
            $sql = "select userId as tokenId from bUser where userName = ? and userPass=md5(?)";
            $stm = $this->dbcon->prepare($sql);
            $stm->bindParam(1, $userName, PDO::PARAM_STR);
            $stm->bindParam(2, $userPass, PDO::PARAM_STR);
            $stm->execute();
            $result = returnDataSingle($stm->rowCount(),$stm->fetchAll(PDO::FETCH_ASSOC));
        }catch (PDOException $e){
            $result = array("status"=>500,"message"=>"error : ".$e->getMessage());
        }

        $result = json_encode($result);
        return $result;
    });

});