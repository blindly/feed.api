<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Remote extends CI_Controller {

        public function index()
        {
                #phpinfo();
        }

        public function title($url = null)
        {
                $data = null;
                if ( ! $url )
                        $url = $this->input->post('url');

                if ( ! $url )
                        $data = array('error' => 'no url');

                if ( substr( $url, 0, 4 ) !== "http" )
                        $url = "http://$url";

                if (! $data) {
                $str = file_get_contents($url);
                  if(strlen($str)>0){
                    $str = trim(preg_replace('/\s+/', ' ', $str)); // supports line breaks inside <title>
                    preg_match("/\<title\>(.*)\<\/title\>/i",$str,$title); // ignore case
                    if ( count($title) > 1 ) {
                    $data = array(
                        'title' => $title[1],
                    );
                    }
                    else
                        $data = array('error' => 'no title found');
                  }
                }


                $output = array(
                        "status" => "ok",
                        "elapsed_time" => $this->benchmark->elapsed_time(),
                        "memory_usage" => $this->benchmark->memory_usage(),
                        "result" => $data
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
