<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Bus\Query;

use App\Application\Query\Shared\Collection;
use App\Application\Query\Shared\Item;
use App\Application\Query\Shared\QueryBusInterface;
use App\Application\Query\Shared\QueryInterface;
use App\Infrastructure\Shared\Bus\MessageBusExceptionTrait;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Throwable;

final class MessengerQueryBus implements QueryBusInterface
{
    use MessageBusExceptionTrait;

    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @throws Throwable
     */
    public function ask(QueryInterface $query)
    {
        try {
            $envelope = $this->messageBus->dispatch($query);

            /** @var HandledStamp $stamp */
            $stamp = $envelope->last(HandledStamp::class);

            return $stamp->getResult();
        } catch (HandlerFailedException $e) {
            $this->throwException($e);
        }
    }
}
