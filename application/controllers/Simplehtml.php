<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Simplehtml extends CI_Controller {

	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function url( $url = null)
	{
		$url = "http://$url";

		$html = file_get_contents($url);

$doc= new simple_html_dom();
$doc->load($html);

$result = $doc->save();

                $output = array(
                        "status" => "ok",
                        "elapsed_time" => $this->benchmark->elapsed_time(),
                        "memory_usage" => $this->benchmark->memory_usage(),
                        "data" => $data
                );


                if ( isset($_GET['callback']) )
                        echo $_GET['callback'] . '(' . json_encode($output) . ')';
                else {
                        return $this->output
                                                ->set_content_type('application/javascript')
                                                ->set_status_header(200)
                                                ->set_header('Access-Control-Allow-Origin: *')
                                                ->set_output(json_encode( $output ));
                }

	}

}
