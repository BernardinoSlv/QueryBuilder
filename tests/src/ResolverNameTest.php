<?php

namespace BernardinoSlv\QueryBuilder;

use BernardinoSlv\QueryBuilder\Databases\Mysql;
use PHPUnit\Framework\TestCase;

class ResolverNameTest extends TestCase
{
    public function testResolverUsingModelClassInParam()
    {
        $model = new Model(new Mysql);

        $this->assertEquals("model", ResolverName::resolver($model::class));
    }
}
