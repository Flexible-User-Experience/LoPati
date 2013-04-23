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
      //  $pagina = $em->getRepository('ASBAEPageBundle:Page')->findOneBy(array('code' => '003-001'));
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

        /* Calcula els dos mesos posteriors
        $mes2 = $mes1 + 1; $any2 = $any1;
        if ($mes2 == 13) {
            $mes2 = 1; $any2++;
        }
        $mes3 = $mes2 + 1; $any3 = $any2;
        if ($mes3 == 13) {
            $mes3 = 1; $any3++;
        }*/

        // Obte tots els items continguts dins del mes i any seleccionat junt amb els dos mesos anteriors
        $items1 = $em->getRepository('BlogBundle:Pagina')->getActiveItemsFromMonthAndYear($mes1, $any1);
        /*$items2 = $em->getRepository('BlogBundle:Pagina')->getActiveItemsFromMonthAndYear($mes2, $any2);
        $items3 = $em->getRepository('BlogBundle:Pagina')->getActiveItemsFromMonthAndYear($mes3, $any3);
        $itemsNextMonthAndForever = $em->getRepository('BlogBundle:Pagina')->getActiveItemsFromNextMonthAndYearForever($mes1, $any1);*/

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

        /* Calcula la matriu de dies per al mes 2
        $daysMatrix2 = array();
        $init2 = getDate(mktime(0, 0, 0, $mes2, 1, $any2));
        $initWday2 = $init2['wday'];
        $totalDays2 = cal_days_in_month(CAL_GREGORIAN, $mes2, $any2);
        if ($initWday2 == 0) $initWday2 = 7; $initWday2--; // Reajuste del dia inicial de la setmana (lunes=0, domingo=6)
        $num = 1;
        for ($index = $initWday2; $index < ($totalDays2 + $initWday2); $index++) {
            $daysMatrix2[$index] = array('nday' => $num);
            $num++;
        }
        $maxWeeks2 = self::getMaxWeeks($daysMatrix2);*/

        /* Calcula la matriu de dies per al mes 3
        $daysMatrix3 = array();
        $init3 = getDate(mktime(0, 0, 0, $mes3, 1, $any3));
        $initWday3 = $init3['wday'];
        $totalDays3 = cal_days_in_month(CAL_GREGORIAN, $mes3, $any3);
        if ($initWday3 == 0) $initWday3 = 7; $initWday3--; // Reajuste del dia inicial de la setmana (lunes=0, domingo=6)
        $num = 1;
        for ($index = $initWday3; $index < ($totalDays3 + $initWday3); $index++) {
            $daysMatrix3[$index] = array('nday' => $num);
            $num++;
        }
        $maxWeeks3 = self::getMaxWeeks($daysMatrix3);*/

       return $this->render('AgendaBundle:Default:calendari.html.twig', array(
            'pagina' => $pagina,
            'items1' => $items1,
            'maxWeek1' => $maxWeeks1,
            'daysMatrix1' => $daysMatrix1,
            'mes1String' => Utils::getStringMonth($mes1),
            'mes1' => $mes1,
            'any1' => $any1,
            /*'items2' => $items2,
            'maxWeek2' => $maxWeeks2,
            'daysMatrix2' => $daysMatrix2,
            'mes2String' => $this->get('translator')->trans(Utils::getStringMonth($mes2)),
            'mes2' => $mes2,
            'any2' => $any2,
            'items3' => $items3,
            'maxWeek3' => $maxWeeks3,
            'daysMatrix3' => $daysMatrix3,
            'mes3String' => $this->get('translator')->trans(Utils::getStringMonth($mes3)),
            'mes3' => $mes3,
            'any3' => $any3,
            'itemsNextMonthAndForever' => $itemsNextMonthAndForever,*/
        ));
    }
}
