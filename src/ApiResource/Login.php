<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\Post;
use App\Api\Model\Token;

#[Post(uriTemplate: '/login', routeName: 'api_security_login', output: Token::class)]
class Login
{
    public ?string $email = null;
    public ?string $password = null;
}
