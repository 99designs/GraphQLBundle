<?php
/**
 * Date: 23.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\Parser;

class Token
{
    // Special
    const TYPE_END = 'end';
    const TYPE_IDENTIFIER = 'identifier';
    const TYPE_NUMBER = 'number';
    const TYPE_STRING = 'string';
    const TYPE_ON = 'on';

    const TYPE_QUERY = 'query';
    const TYPE_MUTATION = 'mutation';
    const TYPE_FRAGMENT = 'fragment';


    // Punctuators
    const TYPE_LT = '<';
    const TYPE_GT = '>';
    const TYPE_LBRACE = '{';
    const TYPE_RBRACE = '}';
    const TYPE_LPAREN = '(';
    const TYPE_RPAREN = ')';
    const TYPE_COLON = ' = ';
    const TYPE_COMMA = ',';
    const TYPE_AMP = '&';
    const TYPE_POINT = '.';

    // Keywords
    const TYPE_NULL = 'null';
    const TYPE_TRUE = 'true';
    const TYPE_FALSE = 'false';
    /** @deprecated */
    const TYPE_AS = 'as';
    const TYPE_FRAGMENT_REFERENCE = '...';

    private $data;
    private $type;

    public function __construct($type, $data = null)
    {
        $this->data = $data;
        $this->type = $type;
    }

    public function toString()
    {
        return "<" . $this->getData() . ", " . $this->getType() . ">";
    }

    public function getData()
    {
        return $this->data;
    }

    public function getType()
    {
        return $this->type;
    }
}