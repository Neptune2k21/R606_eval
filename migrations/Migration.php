<?php

declare(strict_types=1);

interface Migration
{
    public function up(PDO $pdo): void;

    public function getVersion(): string;

    public function getDescription(): string;
}
