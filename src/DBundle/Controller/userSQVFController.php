<?php

namespace DBundle\Controller;

use DBundle\Entity\userSQVF;
use SQVFBundle\Entity\sqvf_users;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class userSQVFController extends Controller
{
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder()
            ->select('count(e.id)')
            ->from(userSQVF::class, 'e');
        $count = $qb->getQuery()
                                ->getSingleScalarResult();
        $users = $em->getRepository(userSQVF::class)->findAll();
        return $this->render('DBundle:userSQVF:list.html.twig', array(
            'users' => $users,
            "nbreContrib" => $count,
        ));
    }

    public function editAction()
    {
        return $this->render('DBundle:userSQVF:edit.html.twig', array(
            // ...
        ));
    }

    public function showAction()
    {
        return $this->render('DBundle:userSQVF:show.html.twig', array(
            // ...
        ));
    }

    public function deleteAction()
    {
        return $this->render('DBundle:userSQVF:delete.html.twig', array(
            // ...
        ));
    }

    public function addAction()
    {
        $em = $this->getDoctrine()->getManager();
        $sqvf_em = $this->getDoctrine()->getManager('sqvf');

        $newUsers = $sqvf_em->getRepository(sqvf_users::class)->findAll();
        foreach ($newUsers as $key => $newUser) {
            $nu = new userSQVF;
            $nu->setIdTypeUser($newUser->getIdTypeUser());
            $nu->setIdCentreFiscal($newUser->getIdCentreFiscal());
            $nu->setIdChef($newUser->getIdChef());
            $nu->setFirstName($newUser->getFirstName());
            $nu->setLastName($newUser->getLastName());
            $nu->setFullName($newUser->getFullName());
            $nu->setEmail($newUser->getEmail());
            $nu->setPassword($newUser->getPassword());
            $nu->setHash($newUser->getHash());
            $nu->setLastActivity($newUser->getLastActivity());
            $nu->setArchive($newUser->getArchive());
            $nu->setCreateTime($newUser->getCreateTime());
            $nu->setUpdateTime($newUser->getUpdateTime());
            $em->persist($nu);
            $em->flush();
        }

        $users = $em->getRepository(userSQVF::class)->findAll();
        return $this->render('DBundle:userSQVF:list.html.twig', array(
            'users' => $users,
        ));
    }
}
