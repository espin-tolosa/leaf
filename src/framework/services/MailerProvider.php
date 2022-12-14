<?php declare(strict_types=1);

namespace Leaf\Services;

class MailerProvider
{
    private string $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

		public function getName()
		{
			return $this->name;
		}

    // ...
}