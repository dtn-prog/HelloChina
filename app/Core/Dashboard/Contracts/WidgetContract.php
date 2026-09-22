<?php

namespace App\Core\Dashboard\Contracts;

interface WidgetContract
{
    public function getId(): string;

    public function getName(): string;

    public function getDescription(): string;

    public function getGroup(): string;

    public function getWidth(): int; // 1-12 (grid columns)

    public function getHeight(): string; // sm, md, lg

    public function getData(): array;

    public function getView(): string;

    public function canAccess($user): bool;
}
