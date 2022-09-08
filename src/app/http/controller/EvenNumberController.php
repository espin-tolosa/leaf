<?php declare(strict_types=1);

namespace Set\Framework\App\Http\Controller;

use Set\Framework\App\Routes\Template;

class EvenNumberController {
	public function isEven($request){
		$number = $request->attributes->get('number');
		$isEven = $number % 2 === 0 ? "YES" : "NO";
		
		$template = new Template($request);
		$template->render(['number' => $number, 'isEven' => $isEven ]);

	}
}