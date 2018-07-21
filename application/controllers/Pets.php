<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pets extends CI_Controller {

	function _remap($parameter){
		$this->index($parameter);
	}

	public function index($category = null)
	{
		$this->load->helper('xml');
		$this->load->helper('text');

		$aww_contents = file_get_contents("https://www.reddit.com/r/aww/.json");

		//echo "<pre>";
		//print_r($aww_contents);

		$aww_contents_json = json_decode($aww_contents);
		$stories = $aww_contents_json->data->children;

		//echo "<pre>";
		//print_r($stories);

		$myStories = array();
		$count = 0;

		foreach ($stories as $story) {
			if ( $story->data->distinguished != 'moderator' ) {

				if ( $count < 2 ) {

				$url = $story->data->preview->images[0]->source->url;
				$title = $story->data->title;
				$title = str_replace("Reddit","",$title);

				$data = array(
					'title' => $title,
					'link' => $url,				
 					'guid' => $url,
				);

				array_push($myStories, $data);
				$count++;
				}
			}
		}

		//print_r($myStories);

		$myFeed['feed_name'] = 'Best Pals Pets'; 
		$myFeed['encoding'] = 'utf-8'; // the encoding
		//$myFeed['feed_url'] = 'http://www.MyWebsite.com/feed'; 
		$myFeed['page_description'] = 'Pets are our best pals'; 
		$myFeed['page_language'] = 'en-en'; 
		//$myFeed['creator_email'] = 'mail@me.com';
		$myFeed['stories'] = $myStories;  
		header("Content-Type: application/rss+xml"); 
		 
		$this->load->view('rss_feed', $myFeed);
	}
}

