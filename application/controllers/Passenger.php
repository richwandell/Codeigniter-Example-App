<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Passenger extends CI_Controller {

  public function passengerList()
  {
    $this->output->cache(1440);
    $this->setNoCache();
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

  /**
   * Sets the browser no cache header so that we don't get browser cached pages
   * We don't want that for this type of form.
   */
  private function setNoCache()
  {
    $this->output->set_header('Last-Modified: '.gmdate('D, d M Y H:i:s', time()).' GMT');
    $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
    $this->output->set_header('Cache-Control: post-check=0, pre-check=0');
    $this->output->set_header('Pragma: no-cache');
  }
}