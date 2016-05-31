<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

$imageCheck = function ($request, $response, $next){

    try{
        $image = $request->getUploadedFiles();
        if(empty($image['img'])){
            throw new RuntimeException("expected variable 'img' with file.");
            exit;
        }
        $image = $image['img'];
        if (
        is_array($image->getError())
        ) {
            throw new RuntimeException('Invalid parameters.');
        }
        $tmp_file = $image->getStream()->getMetadata('uri');
        if($image->getSize() > 5000000){
            throw new RuntimeException('Exceeded filesize limit.');
            exit;
        }
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if (false === $ext = array_search(
                $finfo->file($tmp_file),
                array(
                    'jpg' => 'image/jpeg',
                    'png' => 'image/png',
                    'gif' => 'image/gif',
                ),
                true
            )) {
            throw new RuntimeException('Invalid file format.');
            exit;
        }

        $request = $request->withAttribute('image',$image);
        $response = $next($request, $response);
    }
    catch(RuntimeException $e){
        echo $e->getMessage();
    }

    return $response;
};

$tokenCheck = function($request, $response, $next){

    $tokenId = $request->getHeader("tokenId");
    if(count($tokenId)==0){
        $response = $response->withStatus(401,"token not presented ");
    }else{
        $tokenId = $tokenId[0];
        try{
            $sql = "select * from bUser where userId = ?";
            $stm = $this->dbcon->prepare($sql);
            $stm->bindParam(1, $tokenId, PDO::PARAM_STR);
            $stm->execute();
            if($stm->rowCount()>0){
                $response = $next($request, $response);
            }else{
                $response = $response->withStatus(403,"token does not match any available tokens stored");
            }
        }catch (PDOException $e){
            $response = $response->withStatus(500,$e->getMessage());
        }
    }

    return $response;
};