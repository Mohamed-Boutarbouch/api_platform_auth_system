<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Service\VerificationEmailFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
class ResendVerificationController extends AbstractController
{
    public function __construct(
        private EmailVerifier $emailVerifier,
        private VerificationEmailFactory $verificationEmailFactory
    ) {
    }

    #[Route('/resend-verification', name: 'resend_verification', methods: ['POST'])]
    public function resendVerification(Request $request, UserRepository $userRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email'])) {
            return new JsonResponse(['error' => 'Email is required'], 400);
        }

        $user = $userRepository->findOneBy(['email' => $data['email']]);

        if (!$user instanceof User) {
            return new JsonResponse(['error' => 'User not found'], 404);
        }

        if ($user->isVerified()) {
            return new JsonResponse(['message' => 'Email already verified'], 200);
        }

        $email = $this->verificationEmailFactory->createVerificationEmail($user);
        $this->emailVerifier->sendEmailConfirmation($user, $email);

        return new JsonResponse(['message' => 'Verification email sent']);
    }
}
