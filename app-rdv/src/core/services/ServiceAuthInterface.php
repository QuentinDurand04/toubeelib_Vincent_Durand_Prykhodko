<?php

namespace rdv\core\services;

use rdv\core\dto\AuthDTO;
use rdv\core\dto\CredentialsDTO;

interface ServiceAuthInterface {
	public function createUser(CredentialsDTO $credentials, int $role): string;
	public function byCredentials(CredentialsDTO $credentials): AuthDTO;

}
