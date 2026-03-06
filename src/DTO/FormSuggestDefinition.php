<?php

declare(strict_types=1);

namespace PhpSoftBox\Forms\DTO;

use InvalidArgumentException;

use function is_array;
use function strtoupper;

final readonly class FormSuggestDefinition
{
    /**
     * @param array<string, mixed> $params
     * @param array<string, string> $headers
     * @param array<string, mixed> $meta
     */
    public function __construct(
        public string $endpoint,
        public string $method = 'GET',
        public string $queryParam = 'q',
        public string $valueKey = 'value',
        public string $labelKey = 'label',
        public ?string $payloadKey = null,
        public int $minLength = 1,
        public int $debounceMs = 250,
        public array $params = [],
        public array $headers = [],
        public array $meta = [],
    ) {
        if ($this->endpoint === '') {
            throw new InvalidArgumentException('Suggest endpoint must not be empty.');
        }

        if ($this->queryParam === '') {
            throw new InvalidArgumentException('Suggest queryParam must not be empty.');
        }

        if ($this->valueKey === '' || $this->labelKey === '') {
            throw new InvalidArgumentException('Suggest valueKey and labelKey must not be empty.');
        }

        if ($this->minLength < 1) {
            throw new InvalidArgumentException('Suggest minLength must be greater than 0.');
        }

        if ($this->debounceMs < 0) {
            throw new InvalidArgumentException('Suggest debounceMs must be greater than or equal to 0.');
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): ?self
    {
        $endpoint = (string) ($data['endpoint'] ?? '');
        if ($endpoint === '') {
            return null;
        }

        return new self(
            endpoint: $endpoint,
            method: strtoupper((string) ($data['method'] ?? 'GET')),
            queryParam: (string) ($data['queryParam'] ?? 'q'),
            valueKey: (string) ($data['valueKey'] ?? 'value'),
            labelKey: (string) ($data['labelKey'] ?? 'label'),
            payloadKey: isset($data['payloadKey']) ? (string) $data['payloadKey'] : null,
            minLength: (int) ($data['minLength'] ?? 1),
            debounceMs: (int) ($data['debounceMs'] ?? 250),
            params: is_array($data['params'] ?? null) ? $data['params'] : [],
            headers: is_array($data['headers'] ?? null) ? $data['headers'] : [],
            meta: is_array($data['meta'] ?? null) ? $data['meta'] : [],
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $result = [
            'endpoint'   => $this->endpoint,
            'method'     => strtoupper($this->method),
            'queryParam' => $this->queryParam,
            'valueKey'   => $this->valueKey,
            'labelKey'   => $this->labelKey,
            'minLength'  => $this->minLength,
            'debounceMs' => $this->debounceMs,
        ];

        if ($this->payloadKey !== null && $this->payloadKey !== '') {
            $result['payloadKey'] = $this->payloadKey;
        }

        if ($this->params !== []) {
            $result['params'] = $this->params;
        }

        if ($this->headers !== []) {
            $result['headers'] = $this->headers;
        }

        if ($this->meta !== []) {
            $result['meta'] = $this->meta;
        }

        return $result;
    }
}
