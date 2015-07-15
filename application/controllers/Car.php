<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Car extends CI_Controller {

  public function carlist()
  {
    $cars_repo = $this->doctrine->em->getRepository('Entities\Car');
    $cars = $cars_repo->findAll();


    $this->load->view('car/list', array(
      "cars" => $cars,
      "message" => $this->session->flashdata('message'),
      "error" => $this->session->flashdata('error'),
      "car_name_error" => $this->session->flashdata('car_name_error')
    ));
  }

  public function addCar()
  {
    if($this->input->post('car_name')){
      $car = new Entities\Car();
      $car->setName($this->input->post('car_name', TRUE));
      $this->doctrine->em->persist($car);
      $this->doctrine->em->flush();
      $this->load->helper('url');
      $this->load->library('user_agent');
      $this->session->set_flashdata("message", "Success! Added a new car");
      $this->load->library('CacheInvalidator');
      CacheInvalidator::delete_cache("car/carlist");
      redirect($this->agent->referrer());
    }
  }

  public function deleteCar()
  {
    if($this->input->post('car_id')){
      $cars_repo = $this->doctrine->em->getRepository('Entities\Car');
      $car = $cars_repo->find($this->input->post('car_id'));
      if($car) {
        $this->doctrine->em->remove($car);
        $this->doctrine->em->flush();
        $this->session->set_flashdata("message", "Success! Removed a car");
      }else{
        $this->session->set_flashdata("message", "Could not find car");
      }
      $this->load->library('user_agent');
      $this->load->helper('url');
      $this->load->library('CacheInvalidator');
      CacheInvalidator::delete_cache("car/carlist");
      redirect($this->agent->referrer());
    }
  }

  public function passengerList($car_id)
  {
//    $this->output->cache(1440);
    $cars_repo = $this->doctrine->em->getRepository('Entities\Car');
    $car = $cars_repo->find($car_id);
    $this->load->view("car/passengerlist", array(
      "passengers" => $car->getPassengers(),
      "car" => $car,
      "message" => $this->session->flashdata('message'),
      "error" => $this->session->flashdata('error'),
      "passenger_first_name_error" => $this->session->flashdata('passenger_first_name_error'),
      "passenger_last_name_error" => $this->session->flashdata('passenger_last_name_error'),
      "passenger_first_name" => $this->session->flashdata('passenger_first_name'),
      "passenger_last_name" => $this->session->flashdata('passenger_last_name')
    ));
  }

  public function partList($car_id)
  {
    $cars_repo = $this->doctrine->em->getRepository('Entities\Car');
    $car = $cars_repo->find($car_id);
    $this->load->view("car/partlist", array(
      "parts" => $car->getParts(),
      "car" => $car,
      "message" => $this->session->flashdata("message"),
      "error" => $this->session->flashdata("error"),
      "part_name_error" => $this->session->flashdata("part_name_error"),
      "part_price_error" => $this->session->flashdata("part_price_error"),
      "part_name" => $this->session->flashdata("part_name"),
      "part_price" => $this->session->flashdata("part_price")
    ));
  }

  public function getCsrfToken()
  {
    header('Content-Type: application/json');
    $this->load->view("csrf", array(
      "message" => $this->session->flashdata("message"),
      "error" => $this->session->flashdata("error")
    ));
  }
}
