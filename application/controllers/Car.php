<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Car extends CI_Controller {

  public function carlist()
  {
    $this->output->cache(1440);
    $cars_repo = $this->doctrine->em->getRepository('Entities\Car');
    $cars = $cars_repo->findAll();
    $this->load->view('car/list', array(
      "cars" => $cars
    ));
  }

  public function passengerList($car_id)
  {
    $this->output->cache(1440);
    $cars_repo = $this->doctrine->em->getRepository('Entities\Car');
    $car = $cars_repo->find($car_id);
    $this->load->view("car/passengerlist", array(
      "passengers" => $car->getPassengers(),
      "car" => $car
    ));
  }

  public function partList($car_id)
  {
    $this->output->cache(1440);
    $cars_repo = $this->doctrine->em->getRepository('Entities\Car');
    $car = $cars_repo->find($car_id);
    $this->load->view("car/partlist", array(
      "parts" => $car->getParts(),
      "car" => $car
    ));
  }

  public function addCar()
  {
    try {
      $this->load->library('user_agent');
      $missing = array();
      if (!$this->input->post('car_name') or trim($this->input->post('car_name')) === "") {
        $missing["car_name"] = "Car Name Required";
      } else {
        $this->session->set_userdata("car_name_value", $this->input->post('car_name'));
      }

      if (count($missing) > 0) {
        throw new MissingParametersException($missing, "addPassenger");
      }

      $car = new Entities\Car();
      $car->setName($this->input->post('car_name', TRUE));
      $this->doctrine->em->persist($car);
      $this->doctrine->em->flush();
      $this->load->helper('url');
      $this->load->library('user_agent');
      $this->session->set_userdata("flash_message", "Success! Added a new car");
      $this->session->unset_userdata("car_name_value");
      $this->load->library('CacheInvalidator');
      CacheInvalidator::delete_cache($this->agent->referrer());
      redirect($this->agent->referrer());
    }catch(MissingParametersException $e){
      $missing = $e->getMissing();
      foreach($missing as $key => $val) {
        $this->session->set_flashdata($key."_error", 'has-error');
        $this->session->set_flashdata("flash_message", $val);
      }
      redirect($this->agent->referrer());
    }
  }

  public function deleteCar()
  {
    if($this->input->post('car_id')){
      $car_id = $this->input->post('car_id');
      $cars_repo = $this->doctrine->em->getRepository('Entities\Car');
      $car = $cars_repo->find($car_id);
      if($car) {
        $this->doctrine->em->remove($car);
        $this->doctrine->em->flush();
        $this->session->set_userdata("flash_message", "Success! Removed a car");
      }else{
        $this->session->set_userdata("flash_message", "Could not find car");
      }
      $this->load->library('user_agent');
      $this->load->helper('url');
      $this->load->library('CacheInvalidator');
      CacheInvalidator::delete_cache("car/carlist");
      CacheInvalidator::delete_cache("car/passengerList/$car_id");
      CacheInvalidator::delete_cache($this->agent->referrer());
      redirect($this->agent->referrer());
    }
  }

  public function getCsrfToken()
  {
    header('Content-Type: application/json');
    $session_data = $this->session->all_userdata();
    $keys = array_filter(array_keys($session_data), function($key){
      return substr($key, 0, 6) === "flash_" || substr($key, -6) === "_value";
    });
    $json_data = array_intersect_key($session_data, array_flip($keys));
    foreach($json_data as $key => $val){
      $this->session->unset_userdata($key);
    }
    $return = array("dynamic_data" => array());
    foreach($json_data as $key => $val){
      $return["dynamic_data"][] = array(
        "selector" => "#$key",
        "data" => substr($key, -6) === "_value" ? array(
          "attr" => "value",
          "data" => $val
        ) : substr($key, -6) === "_error" ? array(
          "attr" => "class",
          "has-error"
        ) : array(
          "attr" => "html",
          "data" => $val
        )
      );
    }
    $return["csrf"] = [$this->security->get_csrf_token_name(), $this->security->get_csrf_hash()];
    echo json_encode($return);
    exit;
  }
}
