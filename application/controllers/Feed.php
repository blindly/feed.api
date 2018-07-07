<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feed extends CI_Controller {

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

		    case "comic":
                        array_push($feeds, 'https://xkcd.com/rss.xml');
                        array_push($feeds, 'http://comicfeeds.chrisbenard.net/view/dilbert/default');
			$feed_limit = 10;

			break;


                    case "crypto":
                        // array_push($feeds, 'https://www.coindesk.com/feed/');
                        array_push($feeds, 'https://cointelegraph.com/rss');
                        array_push($feeds, 'https://cryptocurrencynews.com/feed/');
                        array_push($feeds, 'http://www.bing.com/news/search?q=crypto+currency&qs=n&form=QBNT&sc=8-7&sp=-1&sk=&format=rss');
                        array_push($feeds, 'http://www.bing.com/news/search?q=etherium&qs=n&form=QBNT&sc=8-7&sp=-1&sk=&format=rss');

                        $feed_limit = 45;

                        break;

		    case "bookmarks":
			array_push($feeds, 'https://feeds.pinboard.in/rss/secret:19c5555f9a1a1f1452ef/u:blindly/t:home/');
			$this->simplepie->set_cache_duration (0);
		
			break;

		    case "programming":
                        array_push($feeds, 'https://blogs.msdn.microsoft.com/webdev/feed/');
                        array_push($feeds, 'https://blogs.technet.microsoft.com/cloudplatform/rssfeeds/devblogs?tags=announcement');
                        array_push($feeds, 'https://thenewstack.io/blog/feed/');
                        array_push($feeds, 'http://blog.elementary.io/rss');
                        $feed_limit = 30;

			break;

		    case "browsers":
                        array_push($feeds, 'https://blogs.opera.com/desktop/feed/');
                        array_push($feeds, 'https://blog.mozilla.org/feed/');
                        array_push($feeds, 'https://vivaldi.com/blog/feed/');
                        array_push($feeds, 'https://blog.waterfoxproject.org/rss');
                        array_push($feeds, 'https://www.bing.com/news/search?q=web+browser&qs=n&form=QBNT&sc=8-7&sp=-1&sk=&format=rss');
			$feed_limit = 20;

   			break;

                    case "enterprise":
                        array_push($feeds, 'http://www.bing.com/news/search?q=Dell+EMC&qs=n&form=QBNT&pq=dell+emc&sc=8-7&sp=-1&sk=&format=rss');
                        array_push($feeds, 'http://www.bing.com/news/search?q=vmware&qs=n&form=QBNT&pq=dell+emc&sc=8-7&sp=-1&sk=&format=rss');
                        array_push($feeds, 'http://www.bing.com/news/search?q=servicenow&qs=n&form=QBNT&pq=dell+emc&sc=8-7&sp=-1&sk=&format=rss');
                        array_push($feeds, 'http://feeds.feedburner.com/planetpuppet');
                        array_push($feeds, 'https://servicematters.servicenow.com/feed');
                        array_push($feeds, 'https://www.ansible.com/blog/rss.xml');
                        array_push($feeds, 'https://about.gitlab.com/atom.xml');
			
                        $feed_limit = 20;

                        break;

                    case "startup":

                        array_push($feeds, "https://www.producthunt.com/feed");
                        array_push($feeds, "https://news.ycombinator.com/rss");
                        array_push($feeds, "https://www.designernews.co/?format=rss");
                        array_push($feeds, "https://lobste.rs/rss");

			$feed_limit = 60;
                        break;

		    case "gaming":

			array_push($feeds, "http://feeds.ign.com/ign/pc-all");
			array_push($feeds, "http://www.engadget.com/tag/@gaming/rss.xml");
			array_push($feeds, "http://rss.escapistmagazine.com/videos/list/1.xml");
			array_push($feeds, "http://www.pcgamer.com/rss/");
			array_push($feeds, "http://store.steampowered.com/feeds/news.xml");

			$feed_limit = 50;
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

		    case "cnn":
			array_push($feeds, "http://rss.cnn.com/rss/cnn_topstories.rss");

			break;

		    case "weather":
			array_push($feeds, "https://www.yahoo.com/news/rss/weather");

			break;

		    default:
			array_push($feeds, 'http://mix.chimpfeedr.com/e4df6-Web-Browsers');
			array_push($feeds, 'http://www.marketwatch.com/rss/topstories');
			array_push($feeds, 'https://news.ycombinator.com/rss');
			array_push($feeds, 'http://daily.jstor.org/feed');
			array_push($feeds, 'http://www.engadget.com/tag/@gaming/rss.xml');
			array_push($feeds, 'http://feeds.feedburner.com/BreakingDefense');
			array_push($feeds, 'https://threatpost.com/feed/');
			array_push($feeds, 'http://feeds.bbci.co.uk/news/world/rss.xml');
			array_push($feeds, 'http://www.newsblur.com/social/rss/32048/popular');
			
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
				$link = $item->get_permalink();
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

				/*
				$description = strip_tags( $description );
				$description = html_entity_decode($description, ENT_QUOTES | ENT_HTML5);
				$description = trim(preg_replace('/\s\s+/', ' ', $description));
				$description = htmlspecialchars_decode( $description );
				$description = preg_replace("/&#?[a-z0-9]+;/i","",$description);

				$description = str_replace("&#39;", "'", $description);
				$description = str_replace('&apos;', "'", $description);
				*/

				/*
				$stopwords = array('the', 'of', 'to', 'and', 'a', 'on', 'if', 'is', 'from', 'me', 'it', 'or', 'you', 'with', 'an', 'my', 'also', 'her', 'his', 'has', 'in', 'The', 'as');
				$clean_description = implode(" ", array_diff(explode(" ", $description), $stopwords));

				$analysis = array_count_values(str_word_count($clean_description, 1));
				arsort($analysis);
				*/

				$banned_domains = [
					'docplayer.net',
					'fitnhit.com',
					'dottech.org',
					'download.cnet.com',
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
				//	'analysis' 	=> $analysis,
				);
				
				if ( ! $banned ) {
					array_push($data_array, $data);
					$item_limit++;
				}
			}

			// $data_array = array_map("unserialize", array_unique(array_map("serialize", $data_array)));

			$output = array(
				"status" => "ok",
				"elapsed_time" => $this->benchmark->elapsed_time(),
				"memory_usage" => $this->benchmark->memory_usage(),
				"stories" => $data_array
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
}
