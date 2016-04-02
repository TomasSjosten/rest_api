<?php

include_once('AbstractRESTAPI.php');


class ExampleAPI extends Tomas\REST_API\REST_API
{
  public function __construct($request, $origin)
  {
    parent::__construct($request);
  }

  protected function example()
  {
    if ('GET' === $this->method) {
      if (isset($this->args['name'])) {
        return ['Name' => 'Given name: '.$this->args['name'], 'Last name' => $this->args['lname']];
      }
      return ['name' => "Your name is Jane Doe"];
    } else {
      return ['Error' => "Endpoint '{$this->endpoint}' only accepts GET requests"];
    }
  }
}