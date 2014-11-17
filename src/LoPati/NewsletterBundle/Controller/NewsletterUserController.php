<?php

namespace LoPati\NewsletterBundle\Controller;

use LoPati\NewsletterBundle\Entity\NewsletterUser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class NewsletterUserController extends Controller
{
    /**
     * Get users by email (AJAX)
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return JsonResponse
     */
    public function searchUsersByEmailAsyncAction(Request $request)
    {
        $q = $request->get('q');
        if ($q == null) {
            throw $this->createNotFoundException('Unable to find post parameter Q');
        }

        $limit = $request->get('limit');
        if ($limit == null) {
            throw $this->createNotFoundException('Unable to find post parameter LIMIT');
        }

        $result = array();
        $users = $this->getDoctrine()
            ->getRepository('NewsletterBundle:NewsletterUser')
            ->getUsersByEmail($q, $limit);
        /** @var NewsletterUser $user */
        foreach($users as $user) {
            $result[] = array(
                'id' => $user['id'],
                'email' => $user['email'],
            );
        }

        return new JsonResponse(array(
            'users' => $result,
        ));
    }
}