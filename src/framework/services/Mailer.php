<?php declare(strict_types=1);

namespace Leaf\Services;

class Mailer
{
    private MailerProvider $provider;

    public function __construct($provider)
    {
        $this->provider = $provider;
    }

		public function send() {
			//mimic send email
			return $this->provider->getName();
		}

    // ...
}