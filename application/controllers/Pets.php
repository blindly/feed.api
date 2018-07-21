<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pets extends CI_Controller {

	function _remap($parameter){
		$this->index($parameter);
	}

	public function index($category = null)
	{
		$debug = $this->input->get('debug');
		date_default_timezone_set('America/New_York');
		$this->load->library('simplepie');
		$this->simplepie->cache_location = "/tmp/cache";
		$this->simplepie->set_cache_duration (600);
		#$this->simplepie->set_cache_duration (0);
		
		$feeds = array();
		$feed_limit = 30;

		switch ($category) {

		    case "aww":
                        array_push($feeds, 'https://www.reddit.com/r/aww/.rss');
			$feed_limit = 10;

			break;

		    default:
			array_push($feeds, '');
			
			$feed_limit = 50;
			break;
		}

		$this->simplepie->set_feed_url( $feeds );
		$this->simplepie->set_cache_duration (600);
		$this->simplepie->enable_order_by_date(true);
		$this->simplepie->set_stupidly_fast(true);
		$success = $this->simplepie->init();
		
		if ($success) {
			
			$item_limit = 0;
			$data_array = array();
			
			foreach($this->simplepie->get_items() as $item) {
			
				if ($item_limit == $feed_limit) {
					break;
				}

				if ( $debug ) {
					echo "<pre>";
					print_r($item);
					return;
				}

				$title = $item->get_title();
				$link = $item->get_url();
				$date = $item->get_date();

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
				$title = preg_replace("/<div>(.*?)<\/div>/", "$1", $title);

                                $banned_domains = [
                                        'docplayer.net',
                                        'fitnhit.com',
                                        'dottech.org',
                                        'download.cnet.com',
                                        'komando.com',
                                ];

                                $banned = in_array($domain, $banned_domains);

				$data = array(
					'title' 	=> $title,
					'link' 		=> $link,
					'date' 		=> $item->get_date(DATE_RFC2822),
					'domain' 	=> $domain,
					'banned'   	=> $banned,
					'favicon' 	=> "//www.google.com/s2/favicons?domain=$domain",
					'description' 	=> $description,
				);
				
				if ( ! $banned ) {
					array_push($data_array, $data);
					$item_limit++;
				}
			}

			// $data_array = array_map("unserialize", array_unique(array_map("serialize", $data_array)));

			$output = array(
				'title' => 'Our Best Pals',
				"stories" => $data_array
			);

			//header("Content-Type: application/rss+xml");
			$this->load->view('rss_feed',$output);
		}
	}
}
