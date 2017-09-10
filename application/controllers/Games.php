<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Games extends CI_Controller {

	public function index()
	{
		$gamesAPI = "http://www.reddit.com/r/browsergames/hot.json?limit=100";
		$data = json_decode( file_get_contents($gamesAPI) );

		$games = array();

		$banned = array('self.BrowserGames', 'poll-maker.com', 'newrpg.com', 'youtube.com', 'juegodenavegador.blogspot.com',
				'gameonwebs.blogspot.com', 'reddit.com', 'imgur.com', 'i.redd.it', 'youtu.be');

		foreach ($data->data->children as $each){
			$title = $each->data->title;
			$score = $each->data->score;
			$domain = $each->data->domain;
			$comments = $each->data->permalink;

			if ( ! in_array( $domain, $banned ))
				array_push( $games, $domain );
		}

		$games = array_map('unserialize', array_unique(array_map('serialize', $games)));
		
		$output = array(
			"status" => "ok",
			"elapsed_time" => $this->benchmark->elapsed_time(),
			"memory_usage" => $this->benchmark->memory_usage(),
			"stories" => $games
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
