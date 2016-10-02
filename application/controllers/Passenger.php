<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Passenger
 * @property  CI_Output output
 * @property  CI_Session session
 * @property  CI_User_agent agent
 * @property  Doctrine doctrine
 * @property  CI_Input input
 */
class Passenger extends MY_Controller
{

    public function passengerlist()
    {
        $this->output->cache(3600);
        $p_repo = $this->doctrine->em->getRepository('Entities\Passenger');
        $passengers = $p_repo->findAll();

        $this->load->view('passenger/passengerlist', array(
            "passengers" => $passengers,
        ));
    }

    /**
     * Shows the passenger detail page with information about the car
     * @param $car_id
     */
    public function detail($car_id)
    {
        $this->output->cache(3600);
        $p_repo = $this->doctrine->em->getRepository('Entities\Passenger');
        $p = $p_repo->find($car_id);
        if ($p) {
            $this->load->view("passenger/detail", array(
                "passenger" => $p,
            ));
        } else {
            $this->load->helper('url');
            $this->load->library('user_agent');
            redirect($this->agent->referrer());
        }
    }

    public function addPassenger()
    {
        try {
            $this->load->library('user_agent');
            $missing = array();
            if (!$this->input->post('passenger_first_name') or trim($this->input->post('passenger_first_name')) === "") {
                $missing["passenger_first_name"] = "First Name Required";
            } else {
                $this->session->set_userdata("passenger_first_name_value", $this->input->post('passenger_first_name'));
            }

            if (!$this->input->post('passenger_first_name') or trim($this->input->post('passenger_last_name')) === "") {
                $missing["passenger_last_name"] = "Last Name Required";
            } else {
                $this->session->set_userdata("passenger_last_name_value", $this->input->post('passenger_last_name'));
            }

            if (count($missing) > 0) {
                throw new MissingParametersException($missing, "addPassenger");
            }
            $car_id = $this->input->post('passenger_car');
            $cars_repo = $this->doctrine->em->getRepository('Entities\Car');
            $car = $cars_repo->find($car_id);
            $p = new Entities\Passenger();
            $p->setFirstName($this->input->post('passenger_first_name', true));
            $p->setLastName($this->input->post('passenger_last_name', true));
            $p->setCar($car);
            $this->doctrine->em->persist($p);
            $this->doctrine->em->flush();
            $this->load->helper('url');
            $this->session->set_userdata("message", "Success! Added a new passenger to this car");
            $this->session->unset_userdata("passenger_last_name_value");
            $this->session->unset_userdata("passenger_first_name_value");
            $this->output->delete_cache($this->referrer);
            $this->output->delete_cache("car/passengerlist/$car_id");
            $this->output->delete_cache("car/detail/$car_id");
            $this->output->delete_cache("passenger/passengerlist");
            $this->output->delete_cache("car/carlist");
            redirect($this->agent->referrer());

        } catch (MissingParametersException $e) {
            $missing = $e->getMissing();
            foreach ($missing as $key => $val) {
                $this->session->set_userdata($key."_error", 'has-error');
                $this->session->set_userdata("flash_message", $val);
            }
            $this->load->helper('url');
            $this->load->library('user_agent');
            redirect($this->agent->referrer());
        }
    }

    public function deletePassenger()
    {
        $this->load->library('user_agent');
        $this->load->helper('url');
        //Check if we have the passenger id
        if ($this->input->post('passenger_id')) {
            //Find our passenger
            $p_id = $this->input->post('passenger_id');
            $p_repo = $this->doctrine->em->getRepository('Entities\Passenger');
            $p = $p_repo->find($p_id);
            if ($p) {
                //Remove the passenger
                $this->doctrine->em->remove($p);
                $this->doctrine->em->flush();
                //Set message
                $this->session->set_userdata("flash_message", "Success! Removed a passenger");
                $this->output->delete_cache("car/carlist");
                $car = $p->getCar();
                if ($car) {
                    $car_id = $car->getId();
                    $this->output->delete_cache("car/passengerList/$car_id");
                }
                $this->output->delete_cache("passenger/passengerList");
                $this->output->delete_cache($this->referrer);
            } else {
                $this->session->set_userdata("flash_message", "Could not find passenger");
            }
            redirect($this->agent->referrer());
        } else {
            redirect($this->agent->referrer());
        }
    }
}