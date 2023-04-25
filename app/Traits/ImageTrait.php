<?php 
namespace App\Traits;

Trait ImageTrait{
	public function upload($file){
		$url = $file->store('images');
		// dd($url->extension());
		return[
			"filename" => $file->hashName(),
			"path" => "/" . explode("/", $url)[0]. "/",
			"type" => $file->extension(),
			"size" => $file->getSize(),
		];
		
	}
}
