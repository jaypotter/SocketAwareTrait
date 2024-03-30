<?php

declare(strict_types=1);

namespace Potter\Socket\Aware;

use \Psr\Container\ContainerInterface, \Socket;

trait SocketAwareTrait 
{
    final public function bindSocket(string $address, int $port = null): void
    {
        socket_bind($this->getSocket(), $address, $port ?? 0);
    }
    
    final public function connectSocket(string $address, int $port = null): void
    {
        socket_connect($this->getSocket(), $address, $port);
    }
    
    final public function getSocket(): Socket
    {
        return $this->getContainer()->get('socket');
    }
    
    final public function hasSocket(): bool
    {
        return $this->getContainer()->has('socket');
    }
    
    abstract public function getContainer(): ContainerInterface;
}