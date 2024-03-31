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
    
    final public function hasSocketError(): bool
    {
        return socket_last_error($this->getSocket()) > 0;
    }
    
    final public function readSocket(int $length): string
    {
        return socket_read($this->getSocket(), $length);
    }
    
    final public function writeSocket(string $data): void
    {
        socket_write($this->getSocket(), $data);
    }
    
    abstract public function getContainer(): ContainerInterface;
}