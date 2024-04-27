<?php

namespace App\Controller;

use App\Form\CompleteDailyFormType;
use App\Service\OpenAIService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DailyController extends AbstractController
{
    private $aiService;

    public function __construct(OpenAIService $aiService)
    {
        $this->aiService = $aiService;
    }

    #[Route('/daily', name: 'app_daily')]
    public function index(Request $request): Response
    {
        $streak = $request->getSession()->get('streak');

        // Create the form instance
        $form = $this->createForm(CompleteDailyFormType::class);

        // Handle form submission
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $formData = $form->getData();

            $this->updateStreak($request);
            $this->completeTask($request, $formData);

            return $this->redirectToRoute('app_daily');
        }

        // Render the form template
        return $this->render('daily/index.html.twig', [
            'streak' => $streak,
            'form' => $form->createView(),
            'formSubmitted' => $form->isSubmitted(),
        ]);
    }

    public function completeTask(Request $request, $formData): void
    {
        $email = $request->getSession()->get('email');
        $file = $this->getParameter('kernel.project_dir') . '/src/Data/users.json';
        $jsonData = file_get_contents($file);
        $users = json_decode($jsonData, true);

        $today = new \DateTime();
        $todayDate = $today->format('Y-m-d');
        $form = $this->createForm(CompleteDailyFormType::class);
        $form->handleRequest($request);

        $answerData = [];

        foreach ($form as $field) {
            if (!$field->isValid()) {
                continue;
            }
            $questionText = $field->getConfig()->getOption('attr')['question_text'];
            $answerData[] = [
                'question' => $questionText,
                'answer' => $field->getData()
            ];
        }

        $newScore = $this->aiService->analyzeAnswers($answerData);
        foreach ($users as &$user) {
            if ($user['email'] === $email) {
                foreach ($user['daily_questions'] as &$dailyQuestion) {
                    if ($dailyQuestion['date'] === $todayDate) {
                        $dailyQuestion['questions'] = array_merge($dailyQuestion['questions'], $answerData);

                        break 2;
                    }
                }

                $user['mental_health_score'] = $newScore;

                $user['daily_questions'][] = [
                    'date' => $todayDate,
                    'questions' => $answerData
                ];

                $user['last_completion_date'] = $todayDate;

                $session = $request->getSession();
                $session->set('streak', $user['streak']);
                $session->set('mental_health_score', $user['mental_health_score']);
                $session->set('last_completion_date', $user['last_completion_date']);

                break;
            }
        }

        file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));
    }


    public function updateStreak(Request $request): void
    {
        $email = $request->getSession()->get('email');
        $file = $this->getParameter('kernel.project_dir') . '/src/Data/users.json';
        $jsonData = file_get_contents($file);
        $users = json_decode($jsonData, true);

        $today = new \DateTime();
        $yesterday = (clone $today)->modify('-1 day')->format('Y-m-d'); // Correctly calculate yesterday's date

        $found = false;
        foreach ($users as &$user) {
            if ($user['email'] === $email) {
                if ($user['last_completion_date'] === $yesterday) {
                    $user['streak']++; // Increment the streak if the last completion date was yesterday
                } else {
                    $user['streak'] = 0; // Reset the streak otherwise
                }
                $found = true; // Flag to indicate the user was found and updated
                break;
            }
        }

        if ($found) {
            file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));
            $request->getSession()->set('streak', $user['streak']);
            $request->getSession()->save();
        }
    }


}

