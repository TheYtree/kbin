<?php declare(strict_types=1);

namespace App\DTO;

class SettingsDto
{
    public function __construct(
        public string $KBIN_DOMAIN,
        public string $KBIN_META_TITLE,
        public string $KBIN_META_KEYWORDS,
        public string $KBIN_META_DESCRIPTION,
        public string $KBIN_DEFAULT_LANG,
        public string $KBIN_CONTACT_EMAIL,
        public string $KBIN_MARKDOWN_HOWTO_URL,
        public bool $KBIN_JS_ENABLED,
        public bool $KBIN_FEDERATION_ENABLED,
    ) {
    }
}
