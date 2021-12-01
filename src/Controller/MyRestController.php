<?php

namespace Drupal\custom_rest_api_controller\Controller;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MyRestController extends ControllerBase {

    public function getData() {

        //get the configuration
        $config = \Drupal::config('custom_rest_api_controller.settings');

        //get the stored name or return default
        $name = $config->get('name') ? : 'no name stored yet';

        //return the response
        $res['name'] = $name;
        return new JsonResponse($res);
    }

    public function postData(Request $req) {

        //decode the json data
        $data = Json::decode($req->getContent());

        //check if the name property is present or not
        if ( !isset($data['name']) ){
            //return the error response
            $res['error_code'] = 404;
            $res['error_msg'] = 'name property is missing, cannot store the configuration';
        } else {

            //if the name property is present in the json data, store in a drupal configuration
            $config = \Drupal::service('config.factory')->getEditable('custom_rest_api_controller.settings');
            $config->set('name', $data['name'])->save();

            //return the success response
            $res['result'] = 'Hello '.$data['name'].'! You name stored successfully!';
        }

        return new JsonResponse($res);
    }

}