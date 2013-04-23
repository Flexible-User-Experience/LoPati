<?php

namespace LoPati\AgendaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use LoPati\Utilities\Utils;


class DefaultController extends Controller
{
    // Retorna la quantitat de setmanes necessaries (5 o 6) per a representar un mes segons la matriu de dies pasada per parametre
    protected static function getMaxWeeks($daysMatrix)
    {
        $maxWeeks = 6; $found = false; $index = 35;
        while (!$found && $index < 42) {
            if (isset($daysMatrix[$index])) $found = true;
            $index++;
        }
        if ($found == false) $maxWeeks = 5;
        return $maxWeeks;
    }

    public function agendaAction($year,$month,$day)
    {
        $em = $this->getDoctrine()->getManager();
        $pagines = $em->getRepository('BlogBundle:Pagina')->getActiveItemsFromDayAndMonthAndYear($day,$month,$year);
           $dia=date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));
        return $this->render('AgendaBundle:Default:agenda.html.twig', array('pagines'=>$pagines,'dia'=>$dia));
    }

    public function calendariAction($fletxa=null)
    {
        $em = $this->getDoctrine()->getManager();
        $pagina = null;
        $session = $this->getRequest()->getSession();

       // if ($this->getRequest()->getMethod() == 'POST' || ) {
        $logger = $this->get('logger');
        $logger->debug('fora xmlRequest');
         if ($this->getRequest()->isXmlHttpRequest()){
            $logger = $this->get('logger');
            $logger->debug('[formacion] Valid POST form! submit=');
            $mes1 = $session->get('estatMes');
            $any1 = $session->get('estatAny');
            if ($fletxa == 'esquerra') {
                // Disminueix el mes actual
                $mes1 = $mes1 - 1;
                if ($mes1 == 0) {
                    $mes1 = 12; $any1--;
                }
            } else {
                // Augmenta el mes actual
                $mes1 = $mes1 + 1;
                if ($mes1 == 13) {
                    $mes1 = 1; $any1++;
                }
            }
            // Guarda en sessio el mes i any determinat
            $session->set('estatMes', $mes1);
            $session->set('estatAny', $any1);
        } else {
            // Comprova que l'usuari tingui guardat en sessio un mes i any seleccionat
            if ($session->has('estatMes') && $session->has('estatAny')) {
                // Si existexen els recupera localment
                $mes1 = $session->get('estatMes');
                $any1 = $session->get('estatAny');
            } else {
                // Si NO existeix agafa el mes i any actual
                $mes1 = date('n');
                $any1 = date('Y');
                // Guarda en sessio el mes i any determinat
                $session->set('estatMes', $mes1);
                $session->set('estatAny', $any1);
            }
        }

        // Marca els hits d'esdeveniments de periodes de dates i esdeveniments d'una sola data
        $items1 = $em->getRepository('BlogBundle:Pagina')->getActiveItemsFromMonthAndYear($mes1, $any1);
        $hitsMatrix = array();
        $mesHit = strval($mes1);
        if ($mesHit < 10) $mesHit = '0'.$mesHit;
        $totalDays = cal_days_in_month(CAL_GREGORIAN, $mes1, $any1);
        foreach ($items1 as $item1) {
            for ($i = 1; $i < $totalDays+1; $i++) {
                $dayHit = strval($i);
                if ($dayHit < 10) $dayHit = '0'.$dayHit;
                $currentDayString = $any1 . '-' . $mesHit . '-' . $dayHit;
                if ($currentDayString >= date_format($item1->getStartDate(), 'Y-m-d') && $currentDayString <= date_format($item1->getEndDate(), 'Y-m-d')) {
                    if ($item1->getStartDate() == $item1->getEndDate()) {
                        $hitsMatrix[$i] = 'hit-single';
                    }
                    if (isset($hitsMatrix[$i])) {
                        if ($hitsMatrix[$i] != 'hit-single') {
                            $hitsMatrix[$i] = 'hit-period';
                        }
                    } else {
                        $hitsMatrix[$i] = 'hit-period';
                    }
                }
            }
        }

        // Calcula la matriu de dies per al mes 1
        $daysMatrix1 = array();
        $init1 = getDate(mktime(0, 0, 0, $mes1, 1, $any1));
        $initWday1 = $init1['wday'];
        $totalDays1 = cal_days_in_month(CAL_GREGORIAN, $mes1, $any1);
        if ($initWday1 == 0) $initWday1 = 7; $initWday1--; // Reajuste del dia inicial de la setmana (lunes=0, domingo=6)
        $num = 1;
        for ($index = $initWday1; $index < ($totalDays1 + $initWday1); $index++) {
            $daysMatrix1[$index] = array('nday' => $num);
            $num++;
        }
        $maxWeeks1 = self::getMaxWeeks($daysMatrix1);

       return $this->render('AgendaBundle:Default:calendari.html.twig', array(
            'pagina' => $pagina,
            'items1' => $items1,
            'maxWeek1' => $maxWeeks1,
            'daysMatrix1' => $daysMatrix1,
            'mes1String' => Utils::getStringMonth($mes1),
            'mes1' => $mes1,
            'any1' => $any1,
            'hitsMatrix' => $hitsMatrix,
            'workingDay1' => $this->container->getParameter('workingDay1'),
            'workingDay2' => $this->container->getParameter('workingDay2'),
        ));
    }
}
