<?php
namespace App\Controllers;
use DateTimeImmutable;

class NotificationCollection {
    public function __construct(
        public readonly array $items,
        public readonly int $unreadCount
    ) {}
}