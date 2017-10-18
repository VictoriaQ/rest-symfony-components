<?php

namespace MyApi;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use Symfony\Component\HttpFoundation;
use Symfony\Component\Security\Guard;
use Symfony\Component\Security\Http;
use Symfony\Component\Security\Core\Authentication;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Security\Core\User\InMemoryUserProvider;
use Symfony\Component\Security\Core\User\UserChecker;
use Symfony\Component\HttpKernel;

class NanoFramework implements HttpKernelInterface
{
    protected $matcher;
    protected $dummyContainer;
    protected $firewallMap;
    protected $firewall;
    protected $dispatcher;

    public function __construct(UrlMatcher $matcher, $dummyContainer)
    {
        $this->matcher = $matcher;
        $this->dummyContainer = $dummyContainer;
        $this->firewallMap = new Http\FirewallMap();
        $this->dispatcher = new EventDispatcher();
    }


    public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
    {
        $this->secure();
        $this->firewall = new Http\Firewall($this->firewallMap, $this->dispatcher);
        $this->dispatcher->addListener(HttpKernel\KernelEvents::REQUEST, array($this->firewall, 'onKernelRequest'));
        $event = new HttpKernel\Event\GetResponseEvent($this, $request, $type);
        $this->dispatcher->dispatch(HttpKernel\KernelEvents::REQUEST, $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }

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
        }
    }

    private function secure()
    {
        $requestMatcher = new HttpFoundation\RequestMatcher();
        //Possible new HttpFoundation\RequestMatcher('^/securearea');
        $firewallListeners = array();
        $tokenStorage = new Authentication\Token\Storage\TokenStorage();
        $guardHandler = new Guard\GuardAuthenticatorHandler($tokenStorage, $this->dispatcher);
        $guardAuthenticators = [new Security\LoginTokenAuthenticator(), new Security\TokenAuthenticator()];
        $userProvider = new InMemoryUserProvider(['victoria' => ['password' => 'hola']]);
        $providers = array(new Guard\Provider\GuardAuthenticationProvider($guardAuthenticators, $userProvider, 'guard', new UserChecker()));

        $firewallListeners[] = new Guard\Firewall\GuardAuthenticationListener($guardHandler, new Authentication\AuthenticationProviderManager($providers), 'guard', $guardAuthenticators);
        $this->firewallMap->add($requestMatcher, $firewallListeners);
    }
}
