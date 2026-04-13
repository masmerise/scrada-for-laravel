<?php declare(strict_types=1);

namespace Scrada\Laravel;

use Scrada\Authentication\Credentials;

final readonly class Config
{
    public function __construct(
        public string $key,
        public string $password,
        public string $env,
    ) {}

    public function credentials(): Credentials
    {
        return Credentials::present(
            key: $this->key,
            password: $this->password,
        );
    }

    public function wantsTestEnv(): bool
    {
        return $this->env === 'test';
    }
}
