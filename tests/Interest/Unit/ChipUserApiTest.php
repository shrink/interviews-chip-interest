<?php

declare(strict_types=1);

namespace Tests\Chip\Interest\Unit;

use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Shrink\Chip\Interest\ChipUserApi;
use Shrink\Chip\Interest\User;
use Shrink\Chip\Interest\UserId;

final class ChipUserApiTest extends TestCase
{
    /**
     * @test
     */
    public function UserIsRetrievedFromApiById(): void
    {
        ($response = $this->createMock(ResponseInterface::class))
            ->method('getBody')
            ->willReturn(json_encode([
                'id' => '88224979-406e-4e32-9458-55836e4e1f95',
                'income' => 499999
            ]));

        $response
            ->method('getStatusCode')
            ->willReturn(200);

        $request = $this->createMock(ServerRequestInterface::class);

        ($requests = $this->createMock(ServerRequestFactoryInterface::class))
            ->method('createServerRequest')
            ->with('GET', 'https://www.example.com/users/88224979-406e-4e32-9458-55836e4e1f95')
            ->willReturn($request);

        ($http = $this->createMock(ClientInterface::class))
            ->method('sendRequest')
            ->with($request)
            ->willReturn($response);

        $chipUserApi = new ChipUserApi(
            $http,
            $requests,
            'https://www.example.com'
        );

        $expectedUser = new User(
            $userId = new UserId('88224979-406e-4e32-9458-55836e4e1f95'),
            499999
        );

        $this->assertEquals($expectedUser, $chipUserApi->userById($userId));
    }

    /**
     * @test
     */
    public function UnexpectedResponseFromServerGeneratesException(): void
    {
        $this->expectException(RuntimeException::class);

        ($response = $this->createMock(ResponseInterface::class))
            ->method('getStatusCode')
            ->willReturn(500);

        ($http = $this->createMock(ClientInterface::class))
            ->method('sendRequest')
            ->willReturn($response);

        $chipUserApi = new ChipUserApi(
            $http,
            $this->createMock(ServerRequestFactoryInterface::class),
            'https://www.example.com'
        );

        $chipUserApi->userById(
            new UserId('88224979-406e-4e32-9458-55836e4e1f95')
        );
    }
}
