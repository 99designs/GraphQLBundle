<?php
/**
 * Date: 27.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\GraphQL\Builder;


use Youshido\GraphQLBundle\GraphQL\Schema\Type\ListType;
use Youshido\GraphQLBundle\GraphQL\Schema\Type\TypeInterface;

class FieldListBuilder extends ListBuilder
{

    /**
     * @inheritdoc
     */
    public function add($name, TypeInterface $type, $options = [])
    {
        $listBuilder = parent::add($name, $type, $options);

        if ($type instanceof ListType) {
            if(isset($options['resolve']) && is_callable($options['resolve'])){
                $listBuilder->get($name)->getType()->setResolveFunction($options['resolve']);
            }

            if(isset($options['type'])){
                $listBuilder->get($name)->getType()->setType($options['type']);
            }
        }

        return $listBuilder;
    }

}