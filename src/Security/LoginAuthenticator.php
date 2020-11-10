<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
class LoginAuthenticator extends AbstractGuardAuthenticator
{
   private $passwordEncoder;
   public function __construct(UserPasswordEncoderInterface $passwordEncoder)
   {
       $this->passwordEncoder = $passwordEncoder;
   }
   public function supports(Request $request)
   {
       return $request->get("_route") === "login" && $request->isMethod("POST");
   }
   public function getCredentials(Request $request)
   {
       return [
           'email' => $request->request->get("email"),
           'password' => $request->request->get("password")
       ];
   }
   public function getUser($credentials, UserProviderInterface $userProvider)
   {
       return $userProvider->loadUserByUsername($credentials['email']);
   }
   public function checkCredentials($credentials, UserInterface $user)
   {
       return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
   }
   public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
   {
       return new JsonResponse([
           'error' => $exception->getMessageKey()
       ], 400);
   }
   public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
   {
       return new JsonResponse([
           'result' => true
       ]);
   }
   public function start(Request $request, AuthenticationException $authException = null)
   {
       return new JsonResponse([
           'error' => 'Access Denied'
       ]);
   }
   public function supportsRememberMe()
   {
       return false;
   }
}