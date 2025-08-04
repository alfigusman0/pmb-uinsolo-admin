<?php
class Pages extends CI_Controller
{
   public function __construct()
   {
      parent::__construct();
   }

   public function Err404()
   {
      $this->output->set_status_header('404');
      $this->load->view('layout/404');
   }
}
