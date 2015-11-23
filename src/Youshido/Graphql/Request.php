<?php
/**
 * Date: 23.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\Graphql;


use Youshido\GraphqlParser\Ast\Fragment;
use Youshido\GraphqlParser\Ast\Mutation;
use Youshido\GraphqlParser\Ast\Query;

class Request
{

    private $queries   = [];
    private $fragments = [];
    private $mutations = [];

    /**
     * @return Query[]
     */
    public function getQueries()
    {
        return $this->queries;
    }

    public function addQueries($queries)
    {
        foreach ($queries as $query) {
            $this->queries[] = $query;
        }
    }

    /**
     * @return Fragment[]
     */
    public function getFragments()
    {
        return $this->fragments;
    }

    public function addFragment($fragment)
    {
        $this->fragments[] = $fragment;
    }

    /**
     * @return Mutation[]
     */
    public function getMutations()
    {
        return $this->mutations;
    }

    public function addMutations($mutations)
    {
        foreach ($mutations as $mutation) {
            $this->mutations[] = $mutation;
        }
    }
}