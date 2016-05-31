<?php
/**
 * Created by PhpStorm.
 * User: geanGin
 * Date: 5/23/16
 * Time: 4:47 PM
 */

$app->group('/task',function(){

    $this->get('/byProject',function($req, $res, $args){

    });

    $this->get('/byEmployee',function($req, $res, $args){

    });

    $this->post('/createNew', function($req, $res, $args){

    });

    $this->put('/editInfo',function($req, $res, $args){

    });

    $this->delete('/deleteId', function($req, $res, $args){

    });

});