<?php
/**
 * Date: 23.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\Parser\Ast;


class Literal
{

    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }
}