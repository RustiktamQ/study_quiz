<?php
namespace App\Controllers;
use DateTimeImmutable;

class NotificationDTO {
    public function __construct(
        public readonly string $message,
        public readonly string $link,
        public readonly DateTimeImmutable $createdAt,
        public readonly bool $isRead
    ) {}
}

class NotificationCollection {
    public function __construct(
        public readonly array $items,
        public readonly int $unreadCount
    ) {}
}