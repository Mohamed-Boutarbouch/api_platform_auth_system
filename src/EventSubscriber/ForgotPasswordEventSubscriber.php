<?php

namespace App\EventSubscriber;

use ApiPlatform\Validator\ValidatorInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\MailerService;
use CoopTilleuls\ForgotPasswordBundle\Event\CreateTokenEvent;
use CoopTilleuls\ForgotPasswordBundle\Event\UpdatePasswordEvent;
use CoopTilleuls\ForgotPasswordBundle\Event\UserNotFoundEvent;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class ForgotPasswordEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly MailerService $mailerService,
        private readonly ValidatorInterface $validator,
        private readonly UserRepository $userRepository,
        private readonly Security $security,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
            UserNotFoundEvent::class => 'onUserNotFound',
            CreateTokenEvent::class => 'onCreateToken',
            UpdatePasswordEvent::class => 'onUpdatePassword',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest() || !str_starts_with($event->getRequest()->get('_route'), 'coop_tilleuls_forgot_password')) {
            return;
        }

        // User should not be authenticated on forgot password
        $token = $this->security->getToken();
        if (null !== $token && $token->getUser() instanceof UserInterface) {
            throw new AccessDeniedHttpException();
        }
    }

    public function onCreateToken(CreateTokenEvent $event): void
    {
        $passwordToken = $event->getPasswordToken();
        $user = $passwordToken->getUser();

        $this->mailerService->sendForgotPassword($user, $passwordToken->getToken());
    }

    public function onUpdatePassword(UpdatePasswordEvent $event): void
    {
        $passwordToken = $event->getPasswordToken();
        /** @var User $user */
        $user = $passwordToken->getUser();
        $user->setPlainPassword($event->getPassword());

        $this->validator->validate($user);

        $this->userRepository->upgradePassword($user, $this->userPasswordHasher->hashPassword($user, $event->getPassword()));
    }

    /**
     * {@inheritdoc}
     */
    public function onUserNotFound(UserNotFoundEvent $event): void
    {
        // $context = $event->getContext();
        // Not action if not user
    }
}
