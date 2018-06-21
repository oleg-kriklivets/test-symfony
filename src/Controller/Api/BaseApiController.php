<?php

namespace App\Controller\Api;

use JMS\Serializer\Context;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validation;

abstract class BaseApiController extends Controller
{
    /**
     * @return Serializer
     */
    protected function getSerializer(): Serializer
    {
        return $this->container->get('jms_serializer');
    }

    /**
     * @param mixed $data
     * @param int $httpStatusCode
     * @param array $serializationGroups
     * @param array $headers
     * @return JsonResponse
     */
    protected function createJsonResponse($data, $httpStatusCode = 200, array $serializationGroups = [], array $headers = []): JsonResponse
    {
        if ($data instanceof ConstraintViolationList) {
            return $this->failedValidationResponse($data, $httpStatusCode, $headers);
        }
        $serializationContext = $this->getSerializationContext();
        if (count($serializationGroups)) {
            $serializationContext->setGroups($serializationGroups);
        }
        $serializedData = $this->getSerializer()->serialize($data, 'json', $serializationContext);
        return new JsonResponse($serializedData, $httpStatusCode, $headers, true);
    }

    /**
     * @param Request $request
     * @param $modelClass
     * @param array $serializationGroups
     * @return array|\JMS\Serializer\scalar|mixed|object
     */
    protected function deserializeRequest(Request $request, $modelClass, array $serializationGroups = [])
    {
        $content = null;

        if ($request->isMethod(Request::METHOD_GET)) {
            $content = $request->getQueryString();
        } else {
            $content = $request->getContent();
        }

        $serializationContext = $this->getDeserializationContext();

        if (count($serializationGroups)) {
            $serializationContext->setGroups($serializationGroups);
        }

        return $this->getSerializer()->deserialize($content, $modelClass, 'json', $serializationContext);
    }

    /**
     * @return Context
     */
    protected function getSerializationContext(): Context
    {
        return $this->initSerializerContext(new SerializationContext());
    }

    /**
     * @return Context
     */
    protected function getDeserializationContext(): Context
    {
        return $this->initSerializerContext(new DeserializationContext());
    }

    /**
     * @param Context $context
     * @return Context
     */
    protected function initSerializerContext(Context $context): Context
    {
        $context->setSerializeNull(true);

        return $context;
    }

    protected function failedValidationResponse(ConstraintViolationList $errors, $code = 400, $headers = []): JsonResponse
    {
        return new JsonResponse(json_encode([
            'errors' => array_map(function (ConstraintViolation $error) {
                return $error->getMessage();
            }, (array)$errors->getIterator())
        ]), $code, $headers, true);
    }
}
