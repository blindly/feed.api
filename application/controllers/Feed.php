<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feed extends CI_Controller {

	public function index()
	{
		$this->load->library('simplepie');
		$this->simplepie->cache_location = BASEPATH .'cache';
		
$feeds = array(
    'http://jon.smajda.com/rss.xml',
    'http://smajda.tumblr.com/rss',
	'http://files.smajda.com/jon/feeds/greader/',
    'http://twitter.com/statuses/user_timeline/14285636.rss',
	'http://feeds.delicious.com/v2/rss/smajda',
    'http://contexts.org/howto/feed/',
    'http://github.com/smajda.atom'
);

		$this->simplepie->set_feed_url( $feeds );
		$this->simplepie->set_cache_duration (600);
		$success = $this->simplepie->init();
		
		if ($success) {
			
			$item_limit = 0;
			$data_array = [];
			
			foreach($this->simplepie->get_items() as $item) {
			
				if ($item_limit == 50) {
					break;
				}
				
				$data = array(
					'title' => $item->get_title(),
					'link' => $item->get_permalink(),
					'pubDate' => $item->get_date('D, d M Y H:i:s T'),
					'description' => htmlspecialchars(strip_tags($item->get_description())),
				);
				
				$data_array.push($data);
			
			}
			
			return $this->output
						->set_content_type('application/javascript')
						->set_status_header(200)
						->set_output(json_encode( $data_array ));
		}
	}
}