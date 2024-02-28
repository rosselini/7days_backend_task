<?php
declare(strict_types=1);

namespace App\Controller;

use App\Form\Type\TimezoneType;
use App\Helpers\InformationDate;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

class TimezoneController extends AbstractController
{
    private const FEBRUARY_MONTH = 2;

    /**
     * @Route("/timezone", name="app_timezone_index")
     */
    public function index(Request $request): Response
    {
        $formTimezone = $this->createForm(TimezoneType::class);

        $formTimezone->handleRequest($request);
        if ($formTimezone->isSubmitted() && $formTimezone->isValid()) {
            $timezoneData = $formTimezone->getData();

            return $this->redirectToRoute('app_timezone_show', [
                'date' => $timezoneData['date'],
                'timezone' => $timezoneData['timezone']
            ]);
        }

        return $this->render('timezone/index.html.twig', [
            'formTimezone' => $formTimezone->createView()
        ]);
    }

    /**
     * @Route("/timezone/show", name="app_timezone_show")
     */
    public function show(Request $request): Response
    {
        $dateTime = new DateTime($request->query->get('date'));

        return $this->render('timezone/show.html.twig', [
            'offsetUTC' => InformationDate::timezoneHasMinutesOffsetToUTC(
                $request->query->get('date'),
                $request->query->get('timezone')
            ),
            'numberOfDaysInMonth' => InformationDate::daysInMonth(
                self::FEBRUARY_MONTH,
                intval($dateTime->format('Y'))
            ),
            'monthNameByDate' => InformationDate::monthNameByDate($dateTime),
            'daysInMonth' => InformationDate::daysInMonth(
                intval($dateTime->format('m')),
                intval($dateTime->format('Y'))
            )
        ]);
    }
}
