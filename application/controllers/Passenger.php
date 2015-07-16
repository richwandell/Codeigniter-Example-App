<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Passenger extends CI_Controller {
  public function addPassenger()
  {
    try {
      $this->load->library('user_agent');
      $missing = array();
      if (!$this->input->post('passenger_first_name') or trim($this->input->post('passenger_first_name')) === "") {
        $missing["passenger_first_name"] = "First Name Required";
      }else{
        $this->session->set_flashdata("passenger_first_name", $this->input->post('passenger_first_name'));
      }

      if (!$this->input->post('passenger_first_name') or trim($this->input->post('passenger_last_name')) === "") {
        $missing["passenger_last_name"] = "Last Name Required";
      }else{
        $this->session->set_flashdata("passenger_last_name", $this->input->post('passenger_last_name'));
      }

      if(count($missing) > 0){
        throw new MissingParametersException($missing, "addPassenger");
      }
      $cars_repo = $this->doctrine->em->getRepository('Entities\Car');
      $car = $cars_repo->find($this->input->post('passenger_car'));
      $p = new Entities\Passenger();
      $p->setFirstName($this->input->post('passenger_first_name', TRUE));
      $p->setLastName($this->input->post('passenger_last_name', TRUE));
      $p->setCar($car);
      $this->doctrine->em->persist($p);
      $this->doctrine->em->flush();
      $this->load->helper('url');
      $this->session->set_flashdata("message", "Success! Added a new passenger to this car");
      $this->session->set_flashdata("passenger_last_name", "");
      $this->session->set_flashdata("passenger_first_name", "");
      $this->load->library('CacheInvalidator');
      CacheInvalidator::delete_cache($this->agent->referrer());
      redirect($this->agent->referrer());

    }catch(MissingParametersException $e){
      $missing = $e->getMissing();
      foreach($missing as $key => $val) {
        $this->session->set_flashdata($key."_error", 'has-error');
        $this->session->set_flashdata("flash_message", $val);
      }
      $this->load->helper('url');
      $this->load->library('user_agent');
      redirect($this->agent->referrer());
    }
  }

  public function passengerList()
  {
    $p_repo = $this->doctrine->em->getRepository('Entities\Passenger');
    $passengers = $p_repo->findAll();

    $this->load->view('passenger/passengerlist', array(
      "passengers" => $passengers,
      "message" => $this->session->flashdata('message'),
      "error" => $this->session->flashdata('error')
    ));
  }

}