<?php

namespace backend\model\enums;

enum IdiomEnum: int
{
    case EN = 1;
    case DE = 2;
    case IT = 3;
    case PT_BR = 4;
    case ZH = 5;
    case ES = 6;
    case FR = 7;

    public function code(): string
    {
        return match($this) {
            self::EN    => 'en',
            self::DE    => 'de',
            self::IT    => 'it',
            self::PT_BR => 'pt-br',
            self::ZH    => 'zh',
            self::ES    => 'es',
            self::FR    => 'fr',
        };
    }

    public static function fromCode(string $code): ?self
    {
        return match($code) {
            'en'    => self::EN,
            'de'    => self::DE,
            'it'    => self::IT,
            'pt-br' => self::PT_BR,
            'zh'    => self::ZH,
            'es'    => self::ES,
            'fr'    => self::FR,
            default => null
        };
    }
}

