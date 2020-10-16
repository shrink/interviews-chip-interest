<?php

declare(strict_types=1);

namespace Shrink\Chip\Interest;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use RuntimeException;

final class ChipUserApi implements ProvidesUserInformation
{
    private ClientInterface $http;

    private ServerRequestFactoryInterface $requests;

    private string $baseUrl;

    public function __construct(
        ClientInterface $http,
        ServerRequestFactoryInterface $requests,
        string $baseUrl
    ) {
        $this->http = $http;
        $this->requests = $requests;
        $this->baseUrl = $baseUrl;
    }

    public function userById(UserId $id): User
    {
        $userData = (object) json_decode($this->userApiResponse($id));

        return new User(
            new UserId((string) $userData->id),
            $userData->income ? (int) $userData->income : null,
        );
    }

    private function userApiResponse(UserId $id): string
    {
        $endpoint = "{$this->baseUrl}/users/{$id}";

        $userResponse = $this->http->sendRequest(
            $this->requests->createServerRequest('GET', $endpoint)
        );

        if ($userResponse->getStatusCode() !== 200) {
            throw new RuntimeException(
                "Expected 200 response, got {$userResponse->getStatusCode()}."
            );
        }

        return (string) $userResponse->getBody();
    }
}
