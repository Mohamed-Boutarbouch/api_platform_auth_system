<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

#[Route('/api', name: 'api_')]
class VerifyEmailController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    #[Route('/verify-email', name: 'verify_email', methods: 'GET')]
    public function verifyUserEmail(Request $request, UserRepository $userRepository): JsonResponse
    {
        $id = $request->query->get('id');

        if (null === $id) {
            return new JsonResponse(['error' => 'Missing user ID'], 400);
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return new JsonResponse(['error' => 'User not found'], 404);
        }

        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            return new JsonResponse([
                'error' => 'Email verification failed',
                'reason' => $exception->getReason()
            ], 400);
        }

        return new JsonResponse([
            'message' => 'Your email address has been verified.',
            'id' => $user->getId()
        ]);
    }
}
