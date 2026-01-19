<?php

namespace App\Libraries;
class core_jwt
{
    protected $applicationId;
    protected $privateKeyPath;

    public function __construct($applicationId = null, $privateKeyPath = null)
    {
        // Configuración por defecto o pasada al instanciar
        $this->applicationId 	= $applicationId ?? 'TU_APPLICATION_ID';
        $this->privateKeyPath 	= $privateKeyPath ?? WRITEPATH . 'vonage_private.key';
    }

    /**
     * Generar JWT
     *
     * @param int $expires Segundos hasta expirar (default 1 hora)
     * @return string JWT
     */
    public function generateJWT($expires = 3600)
    {
        // Cargar librería JWT de Firebase		
		require_once APPPATH . 'ThirdParty/php-jwt/src/JWTExceptionWithPayloadInterface.php';
        require_once APPPATH . 'ThirdParty/php-jwt/src/JWT.php';
        require_once APPPATH . 'ThirdParty/php-jwt/src/Key.php';
        require_once APPPATH . 'ThirdParty/php-jwt/src/BeforeValidException.php';
        require_once APPPATH . 'ThirdParty/php-jwt/src/ExpiredException.php';
        require_once APPPATH . 'ThirdParty/php-jwt/src/SignatureInvalidException.php';
		$privateKey = file_get_contents($this->privateKeyPath);
        $now 		= time();
        $payload 	= [
            'iat' 				=> $now,
            'exp' 				=> $now + $expires,
            'jti' 				=> bin2hex(random_bytes(16)),
            'application_id' 	=> $this->applicationId
        ];
		
        $jwt = \Firebase\JWT\JWT::encode($payload, $privateKey, 'RS256');
		return $jwt;
    }
}
