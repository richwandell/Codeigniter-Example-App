<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Part
 *
 * The Part controller Handles everything related to the Part page.
 * @property  CI_Output output
 * @property  CI_Session session
 * @property  CI_User_agent agent
 * @property  Doctrine doctrine
 * @property  CI_Input input
 */
class Part extends MY_Controller
{
    /**
     * Display the part list
     * This page should be cached for up to 1 day. Browser cache headers will be
     * set so that it will re validate. Server will send back a 304 not modified
     * header.
     *
     * If a car is deleted or a new part is added or deleted this page cache will
     * be invalidated.
     *
     */
    public function partlist()
    {
        $this->output->cache(3600);
        $parts_repo = $this->doctrine->em->getRepository('Entities\Part');
        $parts = $parts_repo->findAll();
        $this->load->view('part/list', array(
            "parts" => $parts,
        ));
    }

    public function detail($part_id)
    {
        $this->output->cache(3600);
        $parts_repo = $this->doctrine->em->getRepository('Entities\Part');
        $part = $parts_repo->find($part_id);
        if ($part) {
            $this->load->view("part/detail", array(
                "part" => $part,
            ));
        } else {
            $this->load->helper('url');
            $this->load->library('user_agent');
            redirect($this->agent->referrer());
        }
    }

    public function addPart()
    {
        $this->load->helper('url');
        $this->load->library('user_agent');
        try {
            $missing = array();
            if (!$this->input->post('part_name') or trim($this->input->post('part_name')) === "") {
                $missing["part_name"] = "Part Name Required";
            } else {
                $this->session->set_userdata("part_name_value", $this->input->post('part_name'));
            }

            if (!$this->input->post('part_price') or trim($this->input->post('part_price')) === "") {
                $missing["part_price"] = "Part Price Required";
            } else {
                $this->session->set_userdata("part_price_value", $this->input->post('part_price'));
            }

            if (count($missing) > 0) {
                throw new MissingParametersException($missing, "addPart");
            }
            $car_id = $this->input->post('part_car');
            $cars_repo = $this->doctrine->em->getRepository('Entities\Car');
            $car = $cars_repo->find($car_id);
            $p = new Entities\Part();
            $p->setName($this->input->post("part_name"));
            $p->setPrice($this->input->post("part_price"));
            $p->setCar($car);
            $this->doctrine->em->persist($p);
            $this->doctrine->em->flush();
            $this->session->set_userdata("flash_message", "Success! Added a new part to this car");
            $this->session->unset_userdata("part_name_name");
            $this->session->unset_userdata("part_price_value");
            $this->output->delete_cache($this->referrer);
            $this->output->delete_cache("car/partlist/$car_id");
            $this->output->delete_cache("car/carlist");
            $this->output->delete_cache("part/partlist");
            redirect($this->agent->referrer());
        } catch (MissingParametersException $e) {
            $missing = $e->getMissing();
            foreach ($missing as $key => $val) {
                $this->session->set_userdata($key."_error", 'has-error');
                $this->session->set_userdata("flash_message", $val);
            }
            redirect($this->agent->referrer());
        }
    }

    public function deletePart()
    {
        $this->load->library('user_agent');
        $this->load->helper('url');
        //Check if we have the car id
        if ($this->input->post('part_id')) {
            //Find our part
            $part_id = $this->input->post('part_id');
            $parts_repo = $this->doctrine->em->getRepository('Entities\Part');
            $part = $parts_repo->find($part_id);
            if ($part) {
                //Remove the part
                $this->doctrine->em->remove($part);
                $this->doctrine->em->flush();
                //Set message
                $this->session->set_userdata("flash_message", "Success! Removed a part");
                $this->output->delete_cache("car/carlist");
                $car_id = $part->getCar()->getId();
                $this->output->delete_cache("car/partlist/$car_id");
                $this->output->delete_cache("part/partlist");
                $this->output->delete_cache($this->referrer);
            } else {
                $this->session->set_userdata("flash_message", "Could not find part");
            }
            redirect($this->agent->referrer());
        } else {
            redirect($this->agent->referrer());
        }
    }
}