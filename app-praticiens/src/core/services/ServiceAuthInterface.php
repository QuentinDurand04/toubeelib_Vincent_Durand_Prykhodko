<?php

namespace praticiens\core\services;

use praticiens\core\dto\AuthDTO;
use praticiens\core\dto\CredentialsDTO;

interface ServiceAuthInterface {
	public function createUser(CredentialsDTO $credentials, int $role): string;
	public function byCredentials(CredentialsDTO $credentials): AuthDTO;

}
