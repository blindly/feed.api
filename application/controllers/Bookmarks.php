<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bookmarks extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->database('bookmarks');

		$ip = hash("ripemd160", $this->input->ip_address() );

		$this->db->order_by('timestamp');
		$this->db->where('ip', $ip);
		$query = $this->db->get('bookmarks');
		$data_array = array();
		foreach ($query->result() as $row)
		{
                        $domain = str_ireplace('www.', '', parse_url($row->url, PHP_URL_HOST));
			
			$data = array(
				'url'           => $row->url,
				'domain'        => $domain,
				'title'         => $row->title,
				'favicon'       => "//www.google.com/s2/favicons?domain=$domain",
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
			"bookmarks" => $data_array
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


	public function fix()
	{
		$this->load->database('bookmarks');
		$query = $this->db->get('bookmarks');

		foreach ($query->result() as $row)
                {
                        $data = array(
                                'url'           => $row->url,
                                'title'         => $row->title,
                        );

			if ( $row->title == "" )
			{

				$url = $row->url;
				$doc = new DOMDocument();
				@$doc->loadHTMLFile($url);
				$xpath = new DOMXPath($doc);
				$title = $xpath->query('//title')->item(0)->nodeValue."\n";

				$this->db->where('url',  $url);
				$this->db->update('bookmarks', array('title' => $title ));
				
				echo $this->db->affected_rows();
				echo "<br>";
			}

                }

	}

	public function info()
	{
                $ip = hash("ripemd160", $this->input->ip_address() );
                echo $ip;
	}

	public function url()
	{
		$url = $this->input->get_post('url');

$doc = new DOMDocument();
@$doc->loadHTMLFile($url);
$xpath = new DOMXPath($doc);
echo $xpath->query('//title')->item(0)->nodeValue."\n";

	}

	public function remove()
	{
		$this->load->database('bookmarks');
		$url = $this->input->get_post('url');
                $ip = hash("ripemd160", $this->input->ip_address() );
                $data = array(
                        'url' => $url,
                        'ip'  => $ip,
                );

		$this->db->where('ip', $ip);
		$this->db->where('url', $url);
		$this->db->delete('bookmarks');

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
		$this->load->database('bookmarks');
		$url = $this->input->get_post('url');
                $ip = hash("ripemd160", $this->input->ip_address() );

		$valid = filter_var("$url", FILTER_VALIDATE_URL);
		
		if (! $valid)
		{
			$status = "not ok";	
		}
		else 
		{

		$doc = new DOMDocument();
		@$doc->loadHTMLFile($url);
		$xpath = new DOMXPath($doc);
		$title = $xpath->query('//title')->item(0)->nodeValue."\n";


		$data = array(
			'url' 	=> $url,
			'ip'  	=> $ip,
			'title' => $title
		);

		$this->db->insert('bookmarks', $data);

		if ( $this->db->affected_rows() > 0 )
			$status = "ok";
		else
			$status = "not ok";
		}

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
