<?php

declare(strict_types=1);

namespace Asaliev\Yii2Bridge\Controller;

use Asaliev\Yii2Bridge\Exception\DispatcherException;
use Asaliev\Yii2Bridge\Messages\RunApplicationMessage;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use yii\base\Application;

class DispatcherController
{
    use HandleTrait;

    /**
     * @var Application
     */
    private Application $yii;

    /**
     * Class constructor
     *
     * @param MessageBusInterface $messageBus Message bus to dispatch messages
     * @param Application $yii Yii2 application instance
     */
    public function __construct(MessageBusInterface $messageBus, Application $yii)
    {
        $this->messageBus = $messageBus;
        $this->yii = $yii;
    }

    /**
     * Dispatches the RunApplicationMessage to the message bus
     *
     * @param Request $request Symfony request object
     * @return Response Symfony response object
     * @throws DispatcherException If the response is not of type Response
     */
    public function run(Request $request): Response
    {
        $message = new RunApplicationMessage($request, $this->yii);

        try {
            $response = $this->handle($message);
        } catch (Exception $e) {
            throw new DispatcherException('Failed to handle the message: ' . get_class($message), $e->getCode(), $e);
        }

        if (!($response instanceof Response)) {
            throw new DispatcherException(
                'Expected \Symfony\Component\HttpFoundation\Response, got: ' . get_debug_type($response)
            );
        }

        return $response;
    }
}
