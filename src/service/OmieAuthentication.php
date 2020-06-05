<?php

define("OMIE_APP_KEY_SIZE", 12, false);     // for validation
define("OMIE_APP_SECRET_SIZE", 32, false);  // for validation

class OmieAuthentication {
    protected string $key;
    protected string $secret;

    public function __construct(string $key, string $secret) {
        $this->setKeys($key, $secret);
    }

    public function getKeys(): array {
        $keys = [
            "app_key" => $this->key,
            "app_secret" => $this->secret
        ];

        return $keys;
    }

    private function setKeys(string $key, string $secret): void {
        
        // variables cleanup
        $keyNumbers = $key;
        preg_replace('/\D/','' , $keyNumbers);
        $secretHash = $secret;
        preg_replace('/[^\d|\w]/','', $secretHash);

        if (strlen($keyNumbers) != 12) {
            throw new Exception(
                $msg = "Erro de autenticação: app_key inválido!",
                $code = 401
            );
        }

        if (strlen($secretHash) != 32) {
            throw new Exception(
                $msg = "Erro de autenticação: app_secret inválido!",
                $code = 401
            );
        }

        $this->key = $key;
        $this->secret = $secret;
    }
}