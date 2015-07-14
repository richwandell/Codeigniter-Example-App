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
      redirect($this->agent->referrer());
    }
  }

  public function passengerList($car_id)
  {
    $cars_repo = $this->doctrine->em->getRepository('Entities\Car');
    $car = $cars_repo->find($car_id);
    $this->load->view("car/passengerlist", array(
      "passengers" => $car->getPassengers(),
      "car" => $car,
      "message" => $this->session->flashdata('message'),
      "error" => $this->session->flashdata('error'),
    ));
  }
}
