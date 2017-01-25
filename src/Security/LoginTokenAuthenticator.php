<?php
namespace MyApi\Security;

use Symfony\Component\Security\Guard;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JSONResponse;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class LoginTokenAuthenticator extends AbstractGuardAuthenticator implements Guard\GuardAuthenticatorInterface {

    public function getCredentials(Request $request)
    {
        if ($request->getPathInfo() != '/login_check') {
            return;
        }
        if (($username = $request->request->get('_username')) &&
            ($password = $request->request->get('_password'))) {

            return [
                'username' => $username,
                'password' => $password,
                ];
        }

        return null;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $username = $credentials['username'];
        return $userProvider->loadUserByUsername($username);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        if ($credentials['username'] === 'victoria' && $credentials['password'] === 'hola') {
            return true;
        }

        // User could not be restored from session due to a wrong password
        throw new \Symfony\Component\Security\Core\Exception\BadCredentialsException();
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        //$request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);

        return new Response(null, 401);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new JSONResponse([
               'token' => 'someToken',
            ]);
    }

    public function supportsRememberMe()
    {
        return false;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse('/');
    }

}
