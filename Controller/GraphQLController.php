<?php

/**
 * Date: 25.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\Controller;

use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Youshido\GraphQLBundle\Exception\UnableToInitializeSchemaServiceException;
use Youshido\GraphQLBundle\Execution\Processor;

class GraphQLController extends AbstractController
{
    protected $container;
    protected $params;

    public function __construct(ContainerInterface $container, ParameterBagInterface $params)
    {
        $this->container = $container;
        $this->params = $params;
    }

    /**
     * @Route("/graphql")
     *
     * @throws \Exception
     *
     * @return JsonResponse
     */
    public function defaultAction()
    {
        try {
            $this->initializeSchemaService();
        } catch (UnableToInitializeSchemaServiceException $e) {
            return new JsonResponse(
                [['message' => 'Schema class ' . $this->getSchemaClass() . ' does not exist']],
                200,
                $this->getResponseHeaders()
            );
        }

        if ($this->container->get('request_stack')->getCurrentRequest()->getMethod() == 'OPTIONS') {
            return $this->createEmptyResponse();
        }

        list($queries, $isMultiQueryRequest) = $this->getPayload();

        $queryResponses = array_map(function ($queryData) {
            return $this->executeQuery($queryData['query'], $queryData['variables']);
        }, $queries);

        $response = new JsonResponse($isMultiQueryRequest ? $queryResponses : $queryResponses[0], 200, $this->getParameter('graphql.response.headers'));

        if ($this->getParameter('graphql.response.json_pretty')) {
            $response->setEncodingOptions($response->getEncodingOptions() | JSON_PRETTY_PRINT);
        }

        return $response;
    }

    protected function createEmptyResponse()
    {
        return new JsonResponse([], 200, $this->getResponseHeaders());
    }

    protected function executeQuery($query, $variables)
    {
        /** @var Processor $processor */
        $processor = $this->container->get('graphql.processor');
        $processor->processPayload($query, $variables);

        return $processor->getResponseData();
    }

    /**
     * @return array
     *
     * @throws \Exception
     */
    protected function getPayload()
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $query = $request->get('query', null);
        $variables = $request->get('variables', []);
        $isMultiQueryRequest = false;
        $queries = [];

        $variables = is_string($variables) ? json_decode($variables, true) ?: [] : [];

        $content = $request->getContent();
        if (!empty($content)) {
            if ($request->headers->has('Content-Type') && 'application/graphql' == $request->headers->get('Content-Type')) {
                $queries[] = [
                    'query' => $content,
                    'variables' => [],
                ];
            } else {
                $params = json_decode($content, true);

                if ($params) {
                    // check for a list of queries
                    if (isset($params[0]) === true) {
                        $isMultiQueryRequest = true;
                    } else {
                        $params = [$params];
                    }

                    foreach ($params as $queryParams) {
                        $query = isset($queryParams['query']) ? $queryParams['query'] : $query;

                        if (isset($queryParams['variables'])) {
                            if (is_string($queryParams['variables'])) {
                                $variables = json_decode($queryParams['variables'], true) ?: $variables;
                            } else {
                                $variables = $queryParams['variables'];
                            }

                            $variables = is_array($variables) ? $variables : [];
                        }

                        $queries[] = [
                            'query' => $query,
                            'variables' => $variables,
                        ];
                    }
                }
            }
        } else {
            $queries[] = [
                'query' => $query,
                'variables' => $variables,
            ];
        }

        return [$queries, $isMultiQueryRequest];
    }

    /**
     * @throws \Exception
     */
    protected function initializeSchemaService()
    {
        if ($this->container->initialized('graphql.schema')) {
            return;
        }

        $this->container->set('graphql.schema', $this->makeSchemaService());
    }

    /**
     * @return object
     *
     * @throws \Exception
     */
    protected function makeSchemaService()
    {
        if ($this->getSchemaService() && $this->container->has($this->getSchemaService())) {
            return $this->container->get($this->getSchemaService());
        }

        $schemaClass = $this->getSchemaClass();
        if (!$schemaClass || !class_exists($schemaClass)) {
            throw new UnableToInitializeSchemaServiceException();
        }

        if ($this->container->has($schemaClass)) {
            return $this->container->get($schemaClass);
        }

        $schema = new $schemaClass();
        if ($schema instanceof ContainerAwareInterface) {
            $schema->setContainer($this->container);
        }

        return $schema;
    }

    /**
     * @return string
     */
    protected function getSchemaClass()
    {
        return $this->getParameter('graphql.schema_class');
    }

    /**
     * @return string
     */
    protected function getSchemaService()
    {
        $serviceName = $this->getParameter('graphql.schema_service');

        if (substr($serviceName ?: '', 0, 1) === '@') {
            return substr($serviceName, 1, strlen($serviceName) - 1);
        }

        return $serviceName;
    }

    protected function getResponseHeaders()
    {
        return $this->getParameter('graphql.response.headers');
    }

    protected function getParameter(string $name): array|bool|string|int|float|\UnitEnum|null
    {
        return $this->params->get($name);
    }
}
