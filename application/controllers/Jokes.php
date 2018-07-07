<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jokes extends CI_Controller {

	public function index()
	{
		$this->load->database('jokes');

		$ip = hash("ripemd160", $this->input->ip_address() );

		$this->db->order_by('timestamp');
		$this->db->where('ip', $ip);
		$query = $this->db->get('jokes');
		$data_array = array();
		foreach ($query->result() as $row)
		{
			$data = array(
				'text'          => $row->text,
				'id'            => $row->id,
			);

			array_push($data_array, $data);

		}

                if ( $this->db->affected_rows() > 0 )
                        $status = "ok";
                else
                        $status = "not ok";

                $output = array(
                        "status" => $status,
                        "elapsed_time" => $this->benchmark->elapsed_time(),
                        "memory_usage" => $this->benchmark->memory_usage(),
			"jokes" => $data_array
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


	public function info()
	{
                $ip = hash("ripemd160", $this->input->ip_address() );
                echo $ip;
	}

	public function remove()
	{
		$this->load->database('jokes');
		$id = $this->input->get_post('id');

                $ip = hash("ripemd160", $this->input->ip_address() );
                $data = array(
                        'id' => $id,
                        'ip'  => $ip,
                );

		$this->db->where('ip', $ip);
		$this->db->where('id', $id);
		$this->db->delete('jokes');

                if ( $this->db->affected_rows() > 0 )
                        $status = "ok";
                else
                        $status = "not ok";

                $output = array(
                        "status" => $status,
                        "elapsed_time" => $this->benchmark->elapsed_time(),
                        "memory_usage" => $this->benchmark->memory_usage(),
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

	public function add()
	{
		$this->load->database('jokes');
		$text = $this->input->get_post('text');
		$id = $this->input->get_post('id');
                $ip = hash("ripemd160", $this->input->ip_address() );

		$data = array(
			'id' 	=> $id,
			'text' 	=> $text,
			'ip'  	=> $ip,
		);

		$this->db->insert('jokes', $data);

		if ( $this->db->affected_rows() > 0 )
			$status = "ok";
		else
			$status = "not ok";

		$output = array(
			"status" => $status,
			"elapsed_time" => $this->benchmark->elapsed_time(),
			"memory_usage" => $this->benchmark->memory_usage(),
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
