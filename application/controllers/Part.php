<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Part extends CI_Controller {
  public function addPart()
  {
    try {
      $missing = array();
      if (!$this->input->post('part_name') or trim($this->input->post('part_name')) === "") {
        $missing["part_name"] = "Part Name Required";
      }else{
        $this->session->set_flashdata("part_name", $this->input->post('part_name'));
      }

      if (!$this->input->post('part_price') or trim($this->input->post('part_price')) === "") {
        $missing["part_price"] = "Part Price Required";
      }else{
        $this->session->set_flashdata("part_price", $this->input->post('part_price'));
      }

      if(count($missing) > 0){
        throw new MissingParametersException($missing, "addPart");
      }
      $cars_repo = $this->doctrine->em->getRepository('Entities\Car');
      $car = $cars_repo->find($this->input->post('part_car'));
      $p = new Entities\Part();
      $p->setName($this->input->post("part_name"));
      $p->setPrice($this->input->post("part_price"));
      $p->setCar($car);
      $this->doctrine->em->persist($p);
      $this->doctrine->em->flush();
      $this->load->helper('url');
      $this->load->library('user_agent');
      $this->session->set_flashdata("message", "Success! Added a new part to this car");
      $this->session->set_flashdata("part_name", "");
      $this->session->set_flashdata("part_price", "");
      redirect($this->agent->referrer());

    }catch(MissingParametersException $e){
      $missing = $e->getMissing();
      foreach($missing as $key => $val) {
        $this->session->set_flashdata($key."_error", 'has-error');
        $this->session->set_flashdata("message", $val);
      }
      $this->load->helper('url');
      $this->load->library('user_agent');
      redirect($this->agent->referrer());
    }
  }
}