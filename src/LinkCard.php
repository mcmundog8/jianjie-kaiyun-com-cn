<?php

namespace App\Render;

use InvalidArgumentException;

class LinkCard
{
    private string $url;
    private string $title;
    private string $description;
    private array $metadata;

    public function __construct(
        string $url = 'https://www.jianjie-kaiyun.com.cn',
        string $title = '开云登录',
        string $description = '开云登录平台，提供安全便捷的登录体验',
        array $metadata = []
    ) {
        $this->url = $this->sanitizeUrl($url);
        $this->title = $title;
        $this->description = $description;
        $this->metadata = $metadata;
    }

    private function sanitizeUrl(string $url): string
    {
        $filtered = filter_var($url, FILTER_SANITIZE_URL);
        if ($filtered === false) {
            throw new InvalidArgumentException('Invalid URL provided');
        }
        return $filtered;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function addMetadata(string $key, string $value): void
    {
        $this->metadata[$key] = $value;
    }

    public function render(): string
    {
        $escapedUrl = htmlspecialchars($this->url, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $escapedTitle = htmlspecialchars($this->title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $escapedDescription = htmlspecialchars($this->description, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $html = '<div class="link-card">' . "\n";
        $html .= '    <a href="' . $escapedUrl . '" target="_blank" rel="noopener noreferrer">' . "\n";
        $html .= '        <div class="link-card-content">' . "\n";
        $html .= '            <h3 class="link-card-title">' . $escapedTitle . '</h3>' . "\n";
        $html .= '            <p class="link-card-description">' . $escapedDescription . '</p>' . "\n";

        if (!empty($this->metadata)) {
            $html .= '            <ul class="link-card-metadata">' . "\n";
            foreach ($this->metadata as $key => $value) {
                $escapedKey = htmlspecialchars($key, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $escapedValue = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $html .= '                <li><span class="meta-key">' . $escapedKey . ':</span> <span class="meta-value">' . $escapedValue . '</span></li>' . "\n";
            }
            $html .= '            </ul>' . "\n";
        }

        $html .= '        </div>' . "\n";
        $html .= '    </a>' . "\n";
        $html .= '</div>' . "\n";

        return $html;
    }

    public static function createDefault(): self
    {
        return new self(
            'https://www.jianjie-kaiyun.com.cn',
            '开云登录',
            '官方开云登录入口，安全可靠'
        );
    }

    public static function fromArray(array $data): self
    {
        $card = new self();
        if (isset($data['url'])) {
            $card->url = $card->sanitizeUrl($data['url']);
        }
        if (isset($data['title'])) {
            $card->title = $data['title'];
        }
        if (isset($data['description'])) {
            $card->description = $data['description'];
        }
        if (isset($data['metadata']) && is_array($data['metadata'])) {
            $card->metadata = $data['metadata'];
        }
        return $card;
    }

    public function toArray(): array
    {
        return [
            'url' => $this->url,
            'title' => $this->title,
            'description' => $this->description,
            'metadata' => $this->metadata,
        ];
    }
}