<?php

declare(strict_types=1);

namespace BernardinoSlv\QueryBuilder;

class ResolverName
{
    public static function resolver(string $className): string
    {
        $temp = explode("\\", $className);
        return strtolower(array_pop($temp));
    }
}
