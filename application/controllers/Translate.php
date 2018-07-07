<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Translate extends CI_Controller {

	public function index()
	{
		$string = 'questo è un testo di prova';
		$data = file_get_contents('https://translate.google.com/translate_a/t?client=p&tl=en&ie=UTF-8&oe=UTF-8&text='.urlencode($string));
		//$data = json_decode($data);

		echo "<pre>";
		print_r($data);
		exit;

		echo $data->src;
	}
}
