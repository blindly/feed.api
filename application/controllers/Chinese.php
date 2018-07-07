<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chinese extends CI_Controller {

	public function index()
	{
		show_404();
	}

        public function all()
        {
                $this->load->database('languages');

                $this->db->cache_on();
                $this->db->order_by('id', 'RANDOM');
                # $this->db->limit(1);
                $query = $this->db->get('chinese');

                $data_array = array();
                foreach ($query->result() as $row)
                {
                        $data = array(
                                'original'     		=> htmlentities($row->original),
                                'translation' 	 	=> htmlentities($row->translation),
                        );

                        array_push($data_array, $data);
                }

                if ( $this->db->affected_rows() > 0 )
                        $status = "ok";
                else
                        $status = "not ok";

                $output = array(
                        "status" => "ok",
                        "elapsed_time" 	=> $this->benchmark->elapsed_time(),
                        "memory_usage" 	=> $this->benchmark->memory_usage(),
                        "data_array" 	=> $data_array,
                );


                if ( isset($_GET['callback']) )
                        echo $_GET['callback'] . '(' . json_encode($output) . ')';
                else {
                        return $this->output
                                                ->set_content_type('application/json')
                                                        ->set_status_header(200)
                                                        ->set_header('Access-Control-Allow-Origin: *')
                                                        ->set_output(json_encode( $output ));
                }

        }


        public function random()
        {
                $this->load->database('languages');

                $this->db->cache_on();
                $this->db->order_by('id', 'RANDOM');
                $this->db->limit(1);
                $query = $this->db->get('chinese');

                if ( $this->db->affected_rows() > 0 )
		{
                        $status = "ok";

			$original = $query->row()->original;
			$translation = $query->row()->translation;

			$output = array(
				"status" 	=> $status,
				"elapsed_time" 	=> $this->benchmark->elapsed_time(),
				"memory_usage" 	=> $this->benchmark->memory_usage(),
				"original" 	=> htmlentities($original),
				"translation" 	=> htmlentities($translation),
			);
		}
                else
		{
                        $status = "not ok";

			$output = array(
				"status" => $status,
				"elapsed_time" => $this->benchmark->elapsed_time(),
				"memory_usage" => $this->benchmark->memory_usage(),
			);
		}

                if ( isset($_GET['callback']) )
                        echo $_GET['callback'] . '(' . json_encode($output) . ')';
                else {
                        return $this->output
                                                ->set_content_type('application/json')
                                                        ->set_status_header(200)
							->set_header('Access-Control-Allow-Origin: *')
							//->set_header('Access-Control-Allow-Origin: https://fivecat.xyz')
                                                        ->set_output(json_encode( $output ));
                }

        }

        public function add()
        {
                $this->load->database('languages');
                $original = $this->input->get_post('original');
                $translation = $this->input->get_post('translation');

                $ip = hash("ripemd160", $this->input->ip_address() );

		if (! $translation)
			show_404();

                $data = array(
                        'original'              => htmlentities($original),
                        'translation'           => htmlentities($translation),
                );

                $this->db->insert('chinese', $data);

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
