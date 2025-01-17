<?php
namespace praticiens\providers\auth;

use praticiens\core\dto\AuthDTO;

use praticiens\core\dto\CredentialsDTO;

interface AuthnProviderInterface{
	public function register(CredentialsDTO $credentials):void;
	public function signin(CredentialsDTO $credentials):AuthDTO;
	public function refresh(AuthDTO $credentials):AuthDTO;
	public function getSignedInUser(string  $atoken):AuthDTO;
}
