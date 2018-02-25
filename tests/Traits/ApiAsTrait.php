<?php

    namespace Tests\Traits;

    trait ApiAsTrait {
        public function apiAs($user, $method, $uri, array $data = [], array $headers = [])
        {
            if($user)
                $headers = array_merge([
                    'Authorization' => 'Bearer ' . \JWTAuth::fromUser($user),
                ], $headers);
    
            return $this->json($method, $uri, $data, $headers);
        }
    }