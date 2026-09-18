<?php
declare(strict_types=1);

function demarrerSession(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function genererJetonCSRF(): string
{
    if (empty($_SESSION['jeton_csrf'])) {
        $_SESSION['jeton_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['jeton_csrf'];
}

function verifierJetonCSRF(?string $jetonRecu): bool
{
    if (empty($_SESSION['jeton_csrf']) || empty($jetonRecu)) {
        return false;
    }
    return hash_equals($_SESSION['jeton_csrf'], $jetonRecu);
}
