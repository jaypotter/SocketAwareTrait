<?php

declare(strict_types=1);

namespace Potter\Socket\Aware;

use \Psr\Container\ContainerInterface, \Socket;

trait SocketAwareTrait 
{
    private array $messageBuffer = [];
    
    final public function bindSocket(string $address, int $port = null): void
    {
        socket_bind($this->getSocket(), $address, $port ?? 0);
    }
    
    final public function connectSocket(string $address, int $port = null): void
    {
        socket_connect($this->getSocket(), $address, $port);
    }
    
    final public function blockSocket(): void
    {
        socket_set_block($this->getSocket());
    }
    
    final public function unblockSocket(): void
    {
        socket_set_nonblock($this->getSocket());
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
        $message = socket_read($this->getSocket(), $length);
        if ($message === false) {
            return '';
        }
        return $message;
    }
    
    final public function readSocketMessage(): string
    {
        $messageBuffer = '';
        while (strlen($message = $this->readSocket(2048))>0) {
            $messageBuffer .= $message;
        }
        array_push($this->messageBuffer, ...array_values(explode("\\n", $messageBuffer)));
        if (count($this->messageBuffer) === 0) {
            return '';
        }
        return array_shift($this->messageBuffer);
    }
    
    final public function writeSocket(string $data): void
    {
        socket_write($this->getSocket(), $data . PHP_EOL);
    }
    
    abstract public function getContainer(): ContainerInterface;
}