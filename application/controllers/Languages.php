<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Languages extends CI_Controller {

	public function index()
	{
		show_404();
		$this->load->database('languages');

		$this->db->cache_on();
		$this->db->order_by('id', 'RANDOM');
		// $this->db->limit(1);
                $query = $this->db->get('languages');

		$data_array = array();
		foreach ($query->result() as $row)	
		{
			$data = array(
				'languageFrom' => $row->languageFrom,
				'original'     => $row->original,
				'languageTo'   => $row->languageTo,
				'translation'  => $row->translation,
				'translationPhonetic' => $row->translationPhonetic,
			);

			array_push($data_array, $data);
		}

                if ( $this->db->affected_rows() > 0 )
                        $status = "ok";
                else
                        $status = "not ok";

                $output = array(
                        "status" => "ok",
                        "elapsed_time" => $this->benchmark->elapsed_time(),
                        "memory_usage" => $this->benchmark->memory_usage(),
			"data_array" => $data_array,
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

        public function all($language = null)
        {
                $this->load->database('languages');

                $this->db->cache_on();
                $this->db->order_by('id', 'RANDOM');
                # $this->db->limit(1);
                if ($language)
                        $this->db->where('languageTo', $language);
                $query = $this->db->get('languages');

                $data_array = array();
                foreach ($query->result() as $row)
                {
                        $data = array(
                                'languageFrom' 		=> htmlentities($row->languageFrom),
                                'original'     		=> htmlentities($row->original),
                                'languageTo'   		=> htmlentities($row->languageTo),
                                'translation' 	 	=> htmlentities($row->translation),
                                'translationPhonetic' 	=> htmlentities($row->translationPhonetic),
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


        public function random($language = null)
        {
                $this->load->database('languages');

                $this->db->cache_on();
                $this->db->order_by('id', 'RANDOM');
		
		if ($language)
			$this->db->where('languageTo', $language);

                $this->db->limit(1);
                $query = $this->db->get('languages');

                if ( $this->db->affected_rows() > 0 )
		{
                        $status = "ok";

			$languageFrom = $query->row()->languageFrom;
			$original = $query->row()->original;
			$languageTo = $query->row()->languageTo;
			$translation = $query->row()->translation;
			$translationPhonetic = $query->row()->translationPhonetic;

			$output = array(
				"status" 	=> $status,
				"elapsed_time" 	=> $this->benchmark->elapsed_time(),
				"memory_usage" 	=> $this->benchmark->memory_usage(),
				"original" 	=> htmlentities($original),
				"translation" 	=> htmlentities($translation),
				"languageTo" 	=> htmlentities($languageTo),
				"phonetic" 	=> htmlentities($translationPhonetic),
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
                $languageFrom = $this->input->get_post('languageFrom');
                $original = $this->input->get_post('original');
                $languageTo = $this->input->get_post('languageTo');
                $translation = $this->input->get_post('translation');
                $translationPhonetic = $this->input->get_post('phonetic');

                $ip = hash("ripemd160", $this->input->ip_address() );

		if (! $languageFrom )
			show_404();

                $data = array(
                        'languageFrom'          => htmlentities($languageFrom),
                        'original'              => htmlentities($original),
                        'originalPhonetic'      => htmlentities($original),
                        'languageTo'            => strtolower($languageTo),
                        'translation'           => htmlentities($translation),
                        'translationPhonetic'   => htmlentities($translationPhonetic),
                );

                $this->db->insert('languages', $data);

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
                                                        ->set_header('Access-Control-Allow-Origin: https://fivecat.xyz')
                                                        ->set_output(json_encode( $output ));
                }

        }


}
