<?php
namespace MyApi\Security;

use Symfony\Component\Security\Guard;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Keychain;
use Lcobucci\JWT\Signer\Rsa\Sha256;


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
        return new JsonResponse(['error' => 'unauthorized'], 401);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new JSONResponse([
               'token' => $this->generateToken($token->getUsername()),
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

    private function generateToken($username)
    {
        $signer = new Sha256();
        $keychain = new Keychain();

        $token = (new Builder())->setIssuer('http://mydomain.com') // Configures the issuer (iss claim)
            ->setAudience('http://mydomain.org') // Configures the audience (aud claim)
            ->setId('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
            ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
            //->setNotBefore(time() + 60) // Configures the time that the token can be used (nbf claim)
            ->setExpiration(time() + 3600) // Configures the expiration time of the token (nbf claim)
            ->set('username', $username) // Configures a new claim, called "username"
            ->sign($signer,  $keychain->getPrivateKey('file://'.__DIR__.'/../../var/jwt/private.pem', 'patata'))
            ->getToken(); // creates a signature using your private key

        return (string)$token;

    }

}
