<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Theme;
use App\Repository\CategoryRepository;
use App\Repository\DreamRepository;
use App\Repository\ThemeRepository;
use App\Service\DreamService;
use App\Service\LocalizationService;
use App\Service\LunaryPhaseService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class PageController extends AbstractController
{
    #[Route('/')]
    final public function indexNoLocale(Request $request): Response
    {
        return $this->redirectToRoute('app_index', ['_locale' => $request->getLocale()]);
    }

    #[Route('/{_locale<%app.supported_locales%>}/', name: 'app_index')]
    final public function index(Request $request, DreamService $dreamService, LunaryPhaseService $lunaryPhaseService, LocalizationService $localizationService): Response
    {
        $dreams = $dreamService->getData();

        return $this->render('pages/index.html.twig', [
            'lunaryPhase' => $lunaryPhaseService->phase($request->getLocale()),
            'hemisphere' => $localizationService->getHemisphere($request),
            'dreams' => array_values($dreams)
        ]);
    }

    #[Route('/{_locale<%app.supported_locales%>}/mentions', name: 'app_mentions')]
    final public function mentions(): Response
    {
        return $this->render('pages/mentions.html.twig');
    }

    #[Route('/{_locale<%app.supported_locales%>}/contact', name: 'app_contact')]
    final public function contact(MailerInterface $mailer, Request $request, TranslatorInterface $translator): Response
    {

        $form = $this->createFormBuilder()
            ->add('name', TextType::class, ['required'=> true, 'attr' => ['placeholder' => 'mail.contact.name']])
            ->add('email', EmailType::class, ['required'=> true, 'attr' => ['placeholder' => 'mail.contact.mail']])
            ->add('subject', TextType::class, ['required'=> true, 'attr' => ['placeholder' => 'mail.contact.subject']])
            ->add('message', TextareaType::class, ['required'=> true, 'attr' => ['placeholder' => 'mail.contact.message']])
            ->add('send', SubmitType::class, ['label' => 'user.send'])
            ->getForm();
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $contactFormData = $form->getData();

            $message = (new Email())
                ->from('contact@reveurdream.com')
                ->addFrom($contactFormData['email'])
                ->to('contact@reveurdream.com')
                ->subject($contactFormData['subject'])
                ->text($contactFormData['message'],
                    'text/plain');
            $mailer->send($message);

            $this->addFlash('success',  $translator->trans('mail.contact.success'));

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('pages/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{_locale<%app.supported_locales%>}/why', name: 'app_why')]
    final public function why(Request $request, DreamService $dreamService, LunaryPhaseService $lunaryPhaseService, LocalizationService $localizationService): Response
    {
        $dreams = $dreamService->getData();

        return $this->render('pages/why.html.twig', [
            'lunaryPhase' => $lunaryPhaseService->phase($request->getLocale()),
            'hemisphere' => $localizationService->getHemisphere($request),
            'dreams' => array_values($dreams)
        ]);
    }


    #[Route('/{_locale<%app.supported_locales%>}/valide', name: 'app_valide')]
    final public function valide(DreamService $dreamService, Request $request, LunaryPhaseService $lunaryPhaseService, LocalizationService $localizationService): Response
    {
        $dreams = $dreamService->getData();

        return $this->render('security/valide.html.twig', [
            'lunaryPhase' => $lunaryPhaseService->phase($request->getLocale()),
            'hemisphere' => $localizationService->getHemisphere($request),
            'dreams' => array_values($dreams)
        ]);
    }
}
