<?php
/**
 * SocialConnect project
 * @author: Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 * @author: Michaël Bilcot <michael.bilcot@neofrag.com>
 */

namespace SocialConnect\OAuth2\Provider;

use SocialConnect\Provider\AccessTokenInterface;
use SocialConnect\Provider\Exception\InvalidAccessToken;
use SocialConnect\Provider\Exception\InvalidResponse;
use SocialConnect\OAuth2\AbstractProvider;
use SocialConnect\OAuth2\AccessToken;
use SocialConnect\Common\Http\Client\Client;

class Discord extends AbstractProvider
{
    public function getBaseUri()
    {
        return 'https://discordapp.com/api/';
    }

    public function getAuthorizeUri()
    {
        return 'https://discordapp.com/api/oauth2/authorize';
    }

    public function getRequestTokenUri()
    {
        return 'https://discordapp.com/api/oauth2/token';
    }

    public function getName()
    {
        return 'discord';
    }

    /**
     * {@inheritdoc}
     */
    public function parseToken($body)
    {
        $result = json_decode($body, true);
        if ($result) {
            return new AccessToken((array)$result);
        }

        throw new InvalidAccessToken('Provider response with not valid JSON');
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentity(AccessTokenInterface $accessToken)
    {
        $response = $this->httpClient->request(
            $this->getBaseUri().'users/@me', [], Client::GET, [
                'Authorization' => 'Bearer '.$accessToken->getToken()
            ]
        );

        if (!$response->isSuccess()) {
            throw new InvalidResponse(
                'API response with error code',
                $response
            );
        }

        $result = $response->json();
        if (!$result) {
            throw new InvalidResponse(
                'API response is not a valid JSON object',
                $response->getBody()
            );
        }

        return $result;
    }
}
