<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Html extends CI_Controller {

	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function get( $url = null, $query = null)
	{
		$url = "https://$url";

		$html = file_get_contents($url);
		$doc = new DOMDocument();
		//$doc->loadHTML($html);
		libxml_use_internal_errors(true);

		@$doc->loadHTML('<?xml encoding="UTF-8">' . $html);

	/*
		foreach($doc->getElementsByTagName("$query") as $result) {
			echo $result;
		}
	*/

		//$data = element_to_obj( $doc->documentElement );

		$data = $this->_element_to_obj($doc->documentElement);

		echo "<pre>";

foreach($doc->getElementsByTagName('head') as $link) {
	print_r($link);
        //echo $link->getAttribute('href');
        //echo "<br />";
}
exit;


                $output = array(
                        "status" => "ok",
                        "elapsed_time" => $this->benchmark->elapsed_time(),
                        "memory_usage" => $this->benchmark->memory_usage(),
                        ///"data" => $doc->documentElement
                        "data" => $this->_element_to_obj($doc->documentElement)
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

function _element_to_obj($element) {
        //print_r($element);
        $obj = array();
        $attr = array();
        $arr = array();
        $name = $element->tagName;
        foreach ($element->attributes as $attribute) {
            $attr[$attribute->name] = $attribute->value;
            if ($attribute->name == 'id') {
                $name .= '#'.$attribute->value;
            }
        }
        if (!empty($attr)) {
            $arr["attributes"] = $attr;
        }
        if ($element->nodeValue != '') {
            $arr["value"] = $element->nodeValue;
        }

        foreach ($element->childNodes as $subElement) {         
            if ($subElement->nodeType == XML_TEXT_NODE) {

            }
            elseif ($subElement->nodeType == XML_CDATA_SECTION_NODE) {

            }
            else {
                $arr["child_nodes"][] = $this->_element_to_obj($subElement);
            }
        }
        $obj[$name] = $arr;
        return $obj;
    }

}
