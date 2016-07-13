<?php
/**
 * slince application library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Application;

use Slince\Event\SubscriberInterface;
use Slince\Event\Event;
use Slince\Application\Exception\NotFoundException;
use Slince\Routing\Exception\RouteNotFoundException;
use Symfony\Component\HttpFoundation\Response;

class ErrorHandler implements SubscriberInterface
{

    function getEvents()
    {
        return [
            EventStore::ERROR_OCCURRED => 'onError',
            EventStore::EXCEPTION_OCCURRED => 'onException'
        ];
    }

    /**
     * 错误发生时的捕获
     * @param Event $event
     */
    function onError(Event $event)
    {
        $event->stopPropagation();
        $kernel = $event->getSubject();
        $application = $kernel->getDispatchedApplication();
        $content = $this->getErrorContent($application, [
            'path' => $kernel->getParameter('request')->getPathInfo(),
            'message' => $event->getArgument(1)
        ]);
        $response = $this->createResponse($content, 500);
        $kernel->sendResponse($response);
    }
    
    /**
     * 异常发生时的默认捕获
     * @param Event $event
     */
    function onException(Event $event)
    {
        $event->stopPropagation();
        $exception = $event->getArgument('exception');
        $kernel = $event->getSubject();
        $application = $kernel->g();
        $parameters = [
            'path' => $kernel->getParameter('request')->getPathInfo(),
            'message' => $exception->getMessage()
        ];
        //基于NotFoundException抛出的异常会渲染成404错误
        if ($exception instanceof NotFoundException || $exception instanceof RouteNotFoundException) {
            $status = 404;
            $content = $this->getNotFoundContent($kernel, $application, $parameters);
        } else {
            $status = 500;
            $content = $this->getErrorContent($application, $parameters);
        }
        $response = $this->createResponse($content, $status);
        $kernel->sendResponse($response);
    }
    
    /**
     * 获取404错误对应的错误内容
     * @param Kernel $kernel
     * @param Application $application
     * @param array $parameters
     * @return string
     */
    protected function getNotFoundContent(Kernel $kernel, Application $application = null, array $parameters = [])
    {
        if (! is_null($application)) {
            $content = $this->renderContentFromView($application, '404', $parameters);
        } else {
            $content = <<<EOT
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>The requested URL {$kernel->getParameter('request')->getPathInfo()} was not found on this server.</p>
</body></html>
EOT;
        }
        return $content;
    }
    
    /**
     * 获取500错误内容
     * @param Application $application
     * @param array $parameters
     * @return string
     */
    protected function getErrorContent(Application $application = null, array $parameters = [])
    {
        //如果错误发生在application级别，则渲染application对应的错误模版
        //否则使用默认错误
        if (! is_null($application)) {
            $content = $this->renderContentFromView($application, '500', $parameters);
        } else {
            $content = <<<EOT
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>500 Internal Server Error</title>
</head><body>
<h1>Internal Server Error</h1>
<p>The server encountered an internal error or
misconfiguration and was unable to complete
your request.</p>
<p>Please contact the server administrator and inform them of the time the error occurred,
and anything you might have done that may have
caused the error.</p>
<p>More information about this error may be available
in the server error log.</p>
</body></html>
EOT;
        }
        return $content;
    }
    
    /**
     * 从Application的错误模板渲染内容
     * @param Application $application
     * @param string $templateName
     * @param array $parameters
     */
    protected function renderContentFromView(Application $application, $templateName, array $parameters = [])
    {
        $viewManager = $application->getViewManager();
        $viewManager->setViewPath($application->getViewPath() . '/error/');
        return $viewManager->load($templateName)->render($parameters);
    }
    
    /**
     * 生成Response对象
     * @param string $content
     * @param int $status
     * @return Response
     */
    protected function createResponse($content, $status)
    {
        return new Response($content, $status);
    }
}