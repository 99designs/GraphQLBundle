<?php
/**
 * Date: 23.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\Parser\Ast;


class Field
{
    /** @var string */
    private $name;

    public function __construct($name)
    {
        $this->name   = $name;
    }
}