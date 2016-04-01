<?php

namespace Tomas\REST_API;

abstract class REST_API
{
  protected $args = null;
  protected $verbs = null;
  protected $method = null;
  protected $endpoint = null;
  protected $allowedIp = [];

  public function __construct($request)
  {
    header("Access-Control-Allow-Orgin: {$_SERVER['HTTP_HOST']}");
    header('Access-Control-Allow-Methods: GET, POST, DELETE');
    header('Content-Type: application/json; charset=utf-8');


    $this->method = $_SERVER['REQUEST_METHOD'];

    /*echo '<pre>';
    print_r($_SERVER);
    echo '</pre>';*/
  
    $this->args = explode('/', rtrim($request, '/'));
    $this->endpoint = array_shift($this->args);
    
    if (array_key_exists(0, $this->args) && !is_numeric($this->args[0])) {
      $this->verb = array_shift($this->args);
    }

    switch ($this->method) {
      case 'DELETE':
      case 'POST':
        $this->args = ($this->cleanInput($_POST));
        break;
      case 'GET':
        $this->args = ($this->cleanInput($_GET));
        break;
      default:
        $this->response("Method not allowed", 405);
        throw new \Exception('Method not allowed');
        break;
    }
  }

  private function controlPermission()
  {
    if (isset($_SERVER['REMOTE_ADDR'])
        && in_array($_SERVER['REMOTE_ADDR'], $this->allowedIp)) {
      return true;
    }
    return false;
  }


  public function processAPI()
  {
    if (method_exists($this, $this->endpoint)) {
      if (isset($this->args)) {
        return $this->response($this->{$this->endpoint}($this->args)); 
      }

      return $this->response($this->{$this->endpoint}());
    }

    return $this->response("No Endpoint given", 404);
  }


  private function cleanInput($dirty)
  {
    $clean = [];
    if (is_array($dirty)) {
      foreach ($dirty as $key => $value) {
        $clean[$key] = strip_tags($value);
      }
    } else {
      $clean = trim(strip_tags($dirty));
    }

    return $clean;
  }


  private function response($data, $statusCode = 200)
  {
    header('HTTP/1.1 '.$statusCode.' '.$this->requestStatus($statusCode));
    return json_encode($data);
  }


  private function requestStatus($statusCode)
  {
    $status = [
      200 => 'OK',
      403 => 'Forbidden',
      404 => 'Not Found',
      405 => 'Method Not Allowed',
      500 => 'Internal Server Error'
    ];

    return ($status[$statusCode]) ? $status[$statusCode] : $status[500];
  }
}
