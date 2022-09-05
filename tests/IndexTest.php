<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class IndexTest extends TestCase
{
	//! PHPUnit assumes that neither the test code nor the tested code emit output or send headers
	/**
	 * @runInSeparateProcess
	 */
    public function testIndex()
    {
			  ob_start();
        $_GET['name'] = 'Fabien';
        //include "index.php";
				include "../index.php";
        $content = ob_get_clean();

        $this->assertEquals('Hello Fabien', $content);
    }
}