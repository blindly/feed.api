<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rss extends CI_Controller {

	function _remap($parameter){
		$this->index($parameter);
	}

	public function index($category = null)
	{
		$feeds = array();

		switch ($category) {
		    case "browsers":
			array_push($feeds, 'http://mix.chimpfeedr.com/e4df6-Web-Browsers');

   			break;

		    case "gaming":

			array_push($feeds, "http://n4g.com/rss/news?channel=pc&sort=latest");
			array_push($feeds, "http://feeds.ign.com/ign/pc-all");
			array_push($feeds, "http://www.engadget.com/tag/@gaming/rss.xml");
			array_push($feeds, "http://rss.escapistmagazine.com/videos/list/1.xml");
			# array_push($feeds, );

			break;

		    case "life":

			array_push($feeds, "http://feeds.gawker.com/lifehacker/excerpts.xml");
			array_push($feeds, "http://zenhabits.net/feed/");
			array_push($feeds, "http://feeds.feedburner.com/Workawesome");
			array_push($feeds, "http://feeds.feedburner.com/Addictivetips");
			array_push($feeds, "http://feeds.feedburner.com/getrichslowly");
			array_push($feeds, "http://feeds.lifehack.org/Lifehack");
			array_push($feeds, "http://feeds.feedburner.com/thesimpledollar");

			break;

		    default:
			array_push($feeds, 'http://mix.chimpfeedr.com/e4df6-Web-Browsers');
			array_push($feeds, 'http://www.marketwatch.com/rss/topstories');
			array_push($feeds, 'https://news.ycombinator.com/rss');
			array_push($feeds, 'http://daily.jstor.org/feed');
			array_push($feeds, 'http://www.engadget.com/tag/@gaming/rss.xml');
			array_push($feeds, 'http://feeds.feedburner.com/BreakingDefense');
			array_push($feeds, 'http://www.faroo.com/api?q=&start=1&length=10&l=en&src=news&f=rss');
			array_push($feeds, 'https://threatpost.com/feed/');
			array_push($feeds, 'http://feeds.gawker.com/lifehacker/full');
			array_push($feeds, 'http://www.technewsworld.com/perl/syndication/rssfull.pl');
			array_push($feeds, 'http://feeds.bbci.co.uk/news/world/rss.xml');
			array_push($feeds, 'http://www.newsblur.com/social/rss/32048/popular');
			
			break;
		}

		if ($success) {
			
			$item_limit = 0;
			$data_array = array();
			
			foreach($this->simplepie->get_items() as $item) {
			
				if ($item_limit == 50) {
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

			$data_array = array_map("unserialize", array_unique(array_map("serialize", $data_array)));

			$output = array( 
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
