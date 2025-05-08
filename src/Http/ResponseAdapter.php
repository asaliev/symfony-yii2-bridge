<?php

declare(strict_types=1);

namespace Asaliev\Yii2Bridge\Http;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use yii\base\Response as YiiBaseResponse;
use yii\web\Cookie as YiiCookie;
use yii\web\Response as YiiWebResponse;

class ResponseAdapter implements ResponseAdapterInterface
{
    public function toSymfonyResponse(YiiBaseResponse $response, string $output): Response
    {
        $headers = $response instanceof YiiWebResponse ? $response->getHeaders()->toArray() : [];
        $statusCode = $response instanceof YiiWebResponse ? $response->getStatusCode() : $response->exitStatus;

        $symfonyResponse = new Response($output, $statusCode, $headers);

        if ($response instanceof YiiWebResponse) {
            foreach ($response->getCookies() as $cookie) {
                $this->setResponseCookie($symfonyResponse, $cookie);
            }
        }

        return $symfonyResponse;
    }

    private function setResponseCookie(Response $response, YiiCookie $cookie): void
    {
        extract((array)$cookie);
        $symfonyCookie = Cookie::create($name, $value, $expire, $path, $domain, $secure, $httpOnly, true, $sameSite);
        $response->headers->setCookie($symfonyCookie);
    }
}
