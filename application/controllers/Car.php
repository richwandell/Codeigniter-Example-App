<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Car
 *
 * The Car controller Handles everything related to the Car page.
 */
class Car extends CI_Controller {
  /**
   * This carList will display a list of cars with the number of parts and
   * passengers in this car.
   *
   * This page will be cached for up to 1 day.
   */
  public function carlist()
  {
    //Sets this thing to cahce for a day
    $this->output->cache(1440);
    //Sets the browser cache header to no cache (we will still send back a 304)
    //So it will still work.
    $this->setNoCache();
    $cars_repo = $this->doctrine->em->getRepository('Entities\Car');
    $cars = $cars_repo->findAll();
    $this->load->view('car/list', array(
      "cars" => $cars
    ));
  }
  /**
   * The passengerList page will display a list of passengers that are in
   * a car. It will use the provided car id to find the passengers for this car.
   *
   * This page is cached for up to 1 day and invalidated when this car is deleted
   * or when a new passenger is added to this car.
   * @param $car_id
   */
  public function passengerList($car_id)
  {
    $this->output->cache(1440);
    $this->setNoCache();
    $cars_repo = $this->doctrine->em->getRepository('Entities\Car');
    $car = $cars_repo->find($car_id);
    $this->load->view("car/passengerlist", array(
      "passengers" => $car->getPassengers(),
      "car" => $car
    ));
  }
  /**
   * The partList page will display a list of parts for the provided car id.
   * This page is cached for up to 1 day and it is invalidated when the car is
   * deleted or when a new part is added to this car.
   * @param $car_id
   */
  public function partList($car_id)
  {
    $this->output->cache(1440);
    $this->setNoCache();
    $cars_repo = $this->doctrine->em->getRepository('Entities\Car');
    $car = $cars_repo->find($car_id);
    $this->load->view("car/partlist", array(
      "parts" => $car->getParts(),
      "car" => $car
    ));
  }

  /**
   * The addCar function is used to relieve a form post request to add a new
   * car.
   */
  public function addCar()
  {
    $this->load->helper('url');
    $this->load->library('user_agent');
    try {
      //Check if we have the car_name variable. If not we add a missing variable
      //to the $missing array.
      $missing = array();
      if (!$this->input->post('car_name') or trim($this->input->post('car_name')) === "") {
        $missing["car_name"] = "Car Name Required";
      } else {
        $this->session->set_userdata("car_name_value", $this->input->post('car_name'));
      }

      //If were missing anything we throw the missing exception and provide the
      //missing parameters array.
      if (count($missing) > 0) {
        //MissingParametersException is a custom exception in the libraries folder
        //It logs the message provided and has a getMissing method used in the catch clause
        throw new MissingParametersException($missing, "addPassenger");
      }

      //Create a new car and set its variables.
      $car = new Entities\Car();
      $car->setName($this->input->post('car_name', TRUE));

      //Save the car
      $this->doctrine->em->persist($car);
      $this->doctrine->em->flush();

      //Set our success message
      $this->session->set_userdata("flash_message", "Success! Added a new car");
      $this->session->unset_userdata("car_name_value");

      //Invalidate the car/carlist page from cache using our CacheInvalidator
      $this->load->library('CacheInvalidator');
      CacheInvalidator::delete_cache($this->agent->referrer());
      redirect($this->agent->referrer());
    }catch(MissingParametersException $e){

      //Set the correct flash messages for the missing parameters and redirect
      //to the previous page where a message will be given to the user.
      $missing = $e->getMissing();
      foreach($missing as $key => $val) {
        $this->session->set_userdata($key."_error", 'has-error');
        $this->session->set_userdata("flash_message", $val);
      }
      redirect($this->agent->referrer());
    }
  }

  /**
   * The deleteCar does what it sounds like. It deletes a car.
   */
  public function deleteCar()
  {
    $this->load->library('user_agent');
    $this->load->helper('url');
    //Check if we have the car id
    if($this->input->post('car_id')){
      //Find our car
      $car_id = $this->input->post('car_id');
      $cars_repo = $this->doctrine->em->getRepository('Entities\Car');
      $car = $cars_repo->find($car_id);
      if($car) {
        //Remove the car
        $this->doctrine->em->remove($car);
        $this->doctrine->em->flush();
        //Set message
        $this->session->set_userdata("flash_message", "Success! Removed a car");
        //Invalidate cache
        $this->load->library('CacheInvalidator');
        CacheInvalidator::delete_cache("car/carlist");
        CacheInvalidator::delete_cache("car/passengerList/$car_id");
        CacheInvalidator::delete_cache("passenger/passengerlist");
        CacheInvalidator::delete_cache($this->agent->referrer());
      }else{
        $this->session->set_userdata("flash_message", "Could not find car");
      }
      redirect($this->agent->referrer());
    }else{
      redirect($this->agent->referrer());
    }
  }

  /**
   * When a page is served from cache it is likely that it will have an invalid
   * csrf token. This function is used to generate a new token as well as gather
   * any messages or dynamic content and send it back as a JSON array loaded
   * through AJAX.
   *
   * This function is a bit long but it will make sense.
   */
  public function getCsrfToken()
  {
    //Set json header
    header('Content-Type: application/json');
    //Get all the session data
    $session_data = $this->session->all_userdata();
    //Filter out things that we don't want to send back
    $keys = array_filter(array_keys($session_data), function($key){
      return substr($key, 0, 6) === "flash_"
      || substr($key, -6) === "_value"
      || substr($key, -6) === "_error";
    });
    $json_data = array_intersect_key($session_data, array_flip($keys));
    //Unset the data that were sending back (flash messages)
    foreach($json_data as $key => $val){
      $this->session->unset_userdata($key);
    }
    //Set up an array to be json_encoded and returned
    $return = array("dynamic_data" => array());
    foreach($json_data as $key => $val){
      $d = array(
        //The selector for jQuery
        "selector" => "#$key"
      );
      //If this is a value
      if(substr($key, -6) === "_value"){
        $d["data"] = array(
          "attr" => "value",
          "data" => $val
        );
      //If this is an error
      }elseif(substr($key, -6) === "_error"){
        $d["data"] = array(
          "attr" => "class",
          "data" => "has-error"
        );
      //Anything else will be inserted as html
      }else{
        $d["data"] = array(
          "attr" => "html",
          "data" => $val
        );
      }
      $return["dynamic_data"][] = $d;
    }
    //Add that csrf token
    $return["csrf"] = [$this->security->get_csrf_token_name(), $this->security->get_csrf_hash()];
    //Send it back!
    echo json_encode($return);
    exit;
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
