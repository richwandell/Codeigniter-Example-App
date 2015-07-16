<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Passenger extends CI_Controller {

  public function passengerList()
  {
    $this->output->cache(1440);
    $p_repo = $this->doctrine->em->getRepository('Entities\Passenger');
    $passengers = $p_repo->findAll();

    $this->load->view('passenger/passengerlist', array(
      "passengers" => $passengers
    ));
  }

  public function addPassenger()
  {
    try {
      $this->load->library('user_agent');
      $missing = array();
      if (!$this->input->post('passenger_first_name') or trim($this->input->post('passenger_first_name')) === "") {
        $missing["passenger_first_name"] = "First Name Required";
      }else{
        $this->session->set_userdata("passenger_first_name_value", $this->input->post('passenger_first_name'));
      }

      if (!$this->input->post('passenger_first_name') or trim($this->input->post('passenger_last_name')) === "") {
        $missing["passenger_last_name"] = "Last Name Required";
      }else{
        $this->session->set_userdata("passenger_last_name_value", $this->input->post('passenger_last_name'));
      }

      if(count($missing) > 0){
        throw new MissingParametersException($missing, "addPassenger");
      }
      $car_id = $this->input->post('passenger_car');
      $cars_repo = $this->doctrine->em->getRepository('Entities\Car');
      $car = $cars_repo->find($car_id);
      $p = new Entities\Passenger();
      $p->setFirstName($this->input->post('passenger_first_name', TRUE));
      $p->setLastName($this->input->post('passenger_last_name', TRUE));
      $p->setCar($car);
      $this->doctrine->em->persist($p);
      $this->doctrine->em->flush();
      $this->load->helper('url');
      $this->session->set_userdata("message", "Success! Added a new passenger to this car");
      $this->session->unset_userdata("passenger_last_name_value");
      $this->session->unset_userdata("passenger_first_name_value");
      $this->load->library('CacheInvalidator');
      CacheInvalidator::delete_cache($this->agent->referrer());
      CacheInvalidator::delete_cache("car/passengerList/$car_id");
      CacheInvalidator::delete_cache("passenger/passengerList");
      CacheInvalidator::delete_cache("car/carlist");
      redirect($this->agent->referrer());

    }catch(MissingParametersException $e){
      $missing = $e->getMissing();
      foreach($missing as $key => $val) {
        $this->session->set_userdata($key."_error", 'has-error');
        $this->session->set_userdata("flash_message", $val);
      }
      $this->load->helper('url');
      $this->load->library('user_agent');
      redirect($this->agent->referrer());
    }
  }
}