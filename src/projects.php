<?php
/**
 * Created by PhpStorm.
 * User: geanGin
 * Date: 5/23/16
 * Time: 4:47 PM
 */

$app->group('/projects',function() use ($tokenCheck, $imageCheck){

    $this->get('/all',function($req, $res, $args){

        try{
            $sql = "select projectId, projectImg, projectName from bProject";
            $stm = $this->dbcon->prepare($sql);
            $stm->execute();
            $result = returnDataCollection($stm->rowCount(),$stm->fetchAll(PDO::FETCH_ASSOC));
        }catch (PDOException $e){
            $result = array('status'=>500,'message'=>'error : '.$e->getMessage());
        }
        $result = json_encode($result);
        return $result;

    });

    $this->get('/id',function($req, $res, $args){
        $input = $req->getParams();
        $projectId = $input['projectId'];

        try{
            $sql = "select * from bProject where projectId = ?";
            $stm = $this->dbcon->prepare($sql);
            $stm->bindParam(1, $projectId, PDO::PARAM_STR);
            $stm->execute();
            $result = returnDataSingle($stm->rowCount(), $stm->fetchAll(PDO::FETCH_ASSOC));
        }catch (PDOException $e){
            $result = array('status'=>401,'message'=>'error : '.$e->getMessage());
        }
        $result = json_encode($result);
        return $result;
    });

    $this->post('/createNew',function($req, $res, $args){

        $input = $req->getParams();

        $newFileName = "";
        $projectImg = $req->getAttribute('image');
        if($projectImg->getError() === UPLOAD_ERR_OK){
            $uploadFileName = $projectImg->getClientFilename();
            $ext = end(explode(".", $uploadFileName));
            $newFileName = generateRandomUserID().'.'.$ext;
            $newFileLocation = __DIR__.'/../images/projIcn/'.$newFileName;
            $projectImg->moveTo($newFileLocation);
        }

        $newFileName = '/images/projIcn/'.$newFileName;
        $projectName = $input['name'];
        $startDate = $input['startDate'];
        $endDate = $input['endDate'];
        $projectId = generateProjectId($projectName);
        try{
            $sql = "insert into bProject (projectId, projectImg, projectName, projectStartDate, projectEndDate) value (?,?,?,?,?)";
            $stm = $this->dbcon->prepare($sql);
            $stm->bindParam(1, $projectId, PDO::PARAM_STR);
            $stm->bindParam(2, $newFileName, PDO::PARAM_STR);
            $stm->bindParam(3, $projectName, PDO::PARAM_STR);
            $stm->bindParam(4, $startDate, PDO::PARAM_STR);
            $stm->bindParam(5, $endDate, PDO::PARAM_STR);
            if($stm->execute()){
                $result = array('status'=>201, 'message'=>'new item inserted');
            } else{
                $result = array('status'=>202, 'message'=>'new item NOT inserted, reason unknown');
            }
        }catch (PDOException $e){
            $result = array('status'=>401, 'message'=>'error : '.$e->getMessage());
        }
        $result = json_encode($result);
        return $result;
    })->add($imageCheck)->add($tokenCheck);

    $this->put('/updateImage',function($req, $res, $args){

        $input = $req->getParams();

        $projectImg = $req->getAttribute('image');
        if($projectImg->getError() === UPLOAD_ERR_OK){
            $uploadFileName = $projectImg->getClientFilename();
            $ext = end(explode(".", $uploadFileName));
            $newFileName = generateRandomUserID().'.'.$ext;
            $newFileLocation = __DIR__.'/../images/projIcn/'.$newFileName;
            $projectImg->moveTo($newFileLocation);
        }

        $newFileName = '/images/projIcn/'.$newFileName;
        $projectId = $input['projectId'];

        try{
            $sql = "update bProject set projectImg = ? where projectId = ?";
            $stm = $this->dbcon->prepare($sql);
            $stm->bindParam(1,$newFileName,PDO::PARAM_STR);
            $stm->bindParam(2,$projectId, PDO::PARAM_STR);
            if($stm->execute()){
                $response = $res->withStatus(201,'new item inserted');
            } else{
                $response = $res->withStatus(202,'new item NOT inserted, reason unknown');
            }
        }catch (PDOException $e){
            $response = $res->withStatus(500,'error : '.$e->getMessage());
        }
        return $response;
    })->add($imageCheck)->add($tokenCheck);

    $this->put('/updateInfo',function($req, $res, $args){

        $input = $req->getParams();

        $keys = array_keys($input);


    })->add($tokenCheck);

    $this->delete('/deleteId',function($req, $res, $args){

    });

});
