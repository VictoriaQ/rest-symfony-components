<?php

namespace MyApi;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class NanoFramework implements HttpKernelInterface
{
    protected $matcher;
    protected $dummyContainer;

    public function __construct(UrlMatcher $matcher, $dummyContainer)
    {
        $this->matcher = $matcher;
        $this->dummyContainer = $dummyContainer;
    }

    public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
    {
        $controllerResolver = new ControllerResolver();
        $argumentResolver = new ArgumentResolver();
        try {
            $request->attributes->add($this->matcher->match($request->getPathInfo()));
            $controller = $controllerResolver->getController($request);
            $controller[0]->dummyContainer = $this->dummyContainer;
            $arguments = $argumentResolver->getArguments($request, $controller);

            return call_user_func_array($controller, $arguments);
        } catch ( Routing\Exception\ResourceNotFoundException $e ) {
            return new Response('Not Found', 404);
        } catch (Exception $e) {
            return new Response('An error occurred', 500);
        } $this->matcher->getContext()->fromRequest($request);
    }
}
