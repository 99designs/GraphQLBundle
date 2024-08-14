<?php

namespace Youshido\GraphQLBundle\Event;

use Symfony\Component\EventDispatcher\GenericEvent;
use Youshido\GraphQL\Field\FieldInterface;
use Youshido\GraphQL\Parser\Ast\Field;

class ResolveEvent extends GenericEvent
{
    /**
     * Constructor.
     *
     * @param FieldInterface $field
     * @param array $astFields
     * @param mixed|null $resolvedValue
     */
    public function __construct(private readonly FieldInterface $field, private readonly array $astFields, private $resolvedValue = null)
    {
        parent::__construct('ResolveEvent', [$this->field, $this->astFields, $this->resolvedValue]);
    }

    /**
     * Returns the field.
     *
     * @return FieldInterface
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Returns the AST fields.
     *
     * @return array
     */
    public function getAstFields()
    {
        return $this->astFields;
    }

    /**
     * Returns the resolved value.
     * 
     * @return mixed|null
     */
    public function getResolvedValue()
    {
        return $this->resolvedValue;
    }

    /**
     * Allows the event listener to manipulate the resolved value.
     * 
     * @param $resolvedValue
     */
    public function setResolvedValue($resolvedValue)
    {
        $this->resolvedValue = $resolvedValue;
    }
}

