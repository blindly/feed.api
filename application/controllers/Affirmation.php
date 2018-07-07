<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Affirmation extends CI_Controller {

	public function index()
	{
		$this->load->database('affirmations');

		$this->db->cache_on();
		$this->db->order_by('id', 'RANDOM');
		$this->db->limit(1);
                $query = $this->db->get('affirmations');

		/*
		$data = array(
			'text' => $query->row()->text,
			'category' => $query->row()->category,
		);
		*/

		$affirmation = $query->row()->text;
		$category = $query->row()->category;

                if ( $this->db->affected_rows() > 0 )
                        $status = "ok";
                else
                        $status = "not ok";

		/*
		$data = array(
			"I am grounded in the experience of the present moment.",
			"I am focused and engaged in the task at hand.",
			"All is well right now.",
			"I am grateful for this moment and find joy in it.",
			"I gently and easily return to the present moment.",
			"I observe my thoughts and actions without judging them.",
			"I accept and embrace all experiences, even unpleasant ones.",
			"I observe my emotions without getting attached to them.",
			"I release the past and live fully in the present moment.",
			"I meditate easily without resistance or anxiety.",
		);
		*/

                $output = array(
                        "status" => "ok",
                        "elapsed_time" => $this->benchmark->elapsed_time(),
                        "memory_usage" => $this->benchmark->memory_usage(),
			"affirmation" => $affirmation,
			"category" => $category,
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

        public function random($category = null)
        {
                $this->load->database('affirmations');

                $this->db->cache_on();
                $this->db->order_by('id', 'RANDOM');
		
		if ($category)
			$this->db->where('category', $category);

                $this->db->limit(1);
                $query = $this->db->get('affirmations');

                $affirmation = $query->row()->text;
                $category = $query->row()->category;

                if ( $this->db->affected_rows() > 0 )
                        $status = "ok";
                else
                        $status = "not ok";

                $output = array(
                        "status" => "ok",
                        "elapsed_time" => $this->benchmark->elapsed_time(),
                        "memory_usage" => $this->benchmark->memory_usage(),
                        "affirmation" => $affirmation,
                        "category" => $category,
                );

                if ( isset($_GET['callback']) )
                        echo $_GET['callback'] . '(' . json_encode($output) . ')';
                else {
                        return $this->output
                                                ->set_content_type('application/json')
                                                        ->set_status_header(200)
							->set_header('Access-Control-Allow-Origin: https://fivecat.xyz')
                                                        ->set_output(json_encode( $output ));
                }

        }
}
