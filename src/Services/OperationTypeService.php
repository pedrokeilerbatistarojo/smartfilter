<?php

namespace Pedrokeilerbatistarojo\Smartfilter\Services;

use Pedrokeilerbatistarojo\Smartfilter\Enums\OperationTypeEnum;

class OperationTypeService
{
    public static function findByValue(string $value): ?OperationTypeEnum
    {
        foreach (OperationTypeEnum::cases() as $enumValue) {
            if ($enumValue->value === $value) {
                return $enumValue;
            }
        }

        return null;
    }

    public static function findByName(string $name): ?OperationTypeEnum
    {
        foreach (OperationTypeEnum::cases() as $enumValue) {
            if ($enumValue->name === $name) {
                return $enumValue;
            }
        }

        return null;
    }
}
