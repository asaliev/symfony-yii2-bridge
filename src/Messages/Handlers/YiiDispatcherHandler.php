<?php

namespace Asaliev\Yii2Bridge\Messages\Handlers;

use Asaliev\Yii2Bridge\Http\ResponseAdapterInterface;
use Asaliev\Yii2Bridge\Messages\RunApplicationMessage;
use Symfony\Component\HttpFoundation\Response;

final class YiiDispatcherHandler
{
    /**
     * @var ResponseAdapterInterface
     */
    private ResponseAdapterInterface $responseAdapter;

    /**
     * Class constructor
     *
     * @param ResponseAdapterInterface $responseAdapter
     */
    public function __construct(ResponseAdapterInterface $responseAdapter)
    {
        $this->responseAdapter = $responseAdapter;
    }

    /**
     * Runs the yii application and converts the response to Symfony response.
     *
     * It is assumed that the necessary yii2 route is already loaded into the Yii2 application.
     *
     * @param RunApplicationMessage $message
     * @return Response
     */
    public function __invoke(RunApplicationMessage $message): Response
    {
        $yii = $message->getApplication();

        ob_start();
        $yii->run();
        $yiiOutput = ob_get_clean();

        return $this->responseAdapter->toSymfonyResponse($yii->getResponse(), $yiiOutput);
    }
}
