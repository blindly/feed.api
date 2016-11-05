<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Json extends CI_Controller {

	function _remap($parameter){
		$this->index($parameter);
	}

	public function index()
	{
		if (! isset($_GET['url'])) {
                        $output = array(
                                "status" => "missing url",
                                "elapsed_time" => $this->benchmark->elapsed_time(),
                                "memory_usage" => $this->benchmark->memory_usage(),
                        );

                        return $this->output
                                                ->set_content_type('application/javascript')
                                                ->set_status_header(200)
                                                ->set_header('Access-Control-Allow-Origin: *')
                                                ->set_output(json_encode( $output ));
		}

		$feed = "http://" . $_GET['url'];

		$this->load->library('simplepie');
		$this->simplepie->cache_location = "/tmp/cache";
		$this->simplepie->set_feed_url( $feed );
		$this->simplepie->set_cache_duration (600);
		$this->simplepie->enable_order_by_date(true);
		#$this->simplepie->set_stupidly_fast(true);
		$success = $this->simplepie->init();
		
		if ($success) {
			
			$item_limit = 0;
			$data_array = array();
			
			foreach($this->simplepie->get_items() as $item) {
			
				if ($item_limit == 30) {
					break;
				}

				$link = $item->get_permalink();

				/* For BING */
				$link = str_replace('amp;', '', $link);
				$checkUrl = parse_url($link, PHP_URL_QUERY);
				parse_str($checkUrl, $params);
				if ( $params ) {
					if ( isset( $params['url'] ) ) {
						$link = $params['url'];
					}
				}

				$domain = str_ireplace('www.', '', parse_url($link, PHP_URL_HOST));

				$description = $item->get_description();
				$description = strip_tags( $description );
				$description = trim(preg_replace('/\s\s+/', ' ', $description));
				$description = htmlspecialchars_decode( $description );
				$description = preg_replace("/&#?[a-z0-9]+;/i","",$description);
	
				/*
				$stopwords = array('the', 'of', 'to', 'and', 'a', 'on', 'if', 'is', 'from', 'me', 'it', 'or', 'you', 'with', 'an', 'my', 'also', 'her', 'his', 'has', 'in', 'The', 'as');
				$clean_description = implode(" ", array_diff(explode(" ", $description), $stopwords));

				$analysis = array_count_values(str_word_count($clean_description, 1));
				arsort($analysis);
				*/

				$data = array(
					'title' 	=> $item->get_title(),
					'link' 		=> "//www.instapaper.com/text?u=$link",
					'date' 		=> $item->get_date(DATE_RFC2822),
					'domain' 	=> $domain,
					'favicon' 	=> "//www.google.com/s2/favicons?domain=$domain",
					'description' 	=> $description,
				//	'analysis' 	=> $analysis,
				);
				
				array_push($data_array, $data);
				$item_limit++;
			}

			// $data_array = array_map("unserialize", array_unique(array_map("serialize", $data_array)));

			$output = array( 
				"status" => "ok",
				"elapsed_time" => $this->benchmark->elapsed_time(),
				"memory_usage" => $this->benchmark->memory_usage(),
				"stories" => $data_array
			);

			return $this->output
						->set_content_type('application/javascript')
						->set_status_header(200)
						->set_header('Access-Control-Allow-Origin: *')
						->set_output(json_encode( $output ));
		}
	}
}
