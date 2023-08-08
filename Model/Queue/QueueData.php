<?php

declare(strict_types=1);

namespace Rally\Checkout\Model\Queue;

/**
 * Data object for rally queue request
 */
class QueueData
{
    /**
     * @param string $action
     * @param string $id
     * @param string[] $ids
     */
    public function __construct(
        private readonly string $action,
        private readonly string $id = '',
        private readonly array $ids = []
    ) {
    }

    /**
     * Retrieve action
     *
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * Retrieve ID to process
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Retrieve IDs to process
     *
     * @return string[]
     */
    public function getIds(): array
    {
        return $this->ids;
    }
}
