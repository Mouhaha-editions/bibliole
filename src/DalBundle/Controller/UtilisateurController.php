<?php

namespace DalBundle\Controller;

use DalBundle\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Utilisateur controller.
 *
 */
class UtilisateurController extends Controller
{

    public function countAction()
    {
        return new Response(count($this->getDoctrine()->getRepository('DalBundle:Utilisateur')->findAll()));
    }

    /**
     * Lists all utilisateur entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->getRepository('DalBundle:Utilisateur')->createQueryBuilder('u')
            ->leftJoin('u.classe', 'c');
        if ($request->get('search')) {
            $searchWords = explode(' ', $request->get('search'));
            $name = null;
            $kw = null;
            foreach ($searchWords AS $k => $search) {
                $qb->andWhere(
                    $qb->expr()->orX(
                        $qb->expr()->orX(
                            $qb->expr()->like('u.nom', ':search' . $k),
                            $qb->expr()->like('u.prenom', ':search' . $k),
                            $qb->expr()->like('c.libelle', ':search' . $k)
                        )
                    )
                );
                $qb->setParameter('search' . $k, '%' . $search . '%');
            }
        }
        $qb->orderBy('u.nom', 'ASC');
        $pagination = $this->get('pkshetlie.pagination')->setDefaults(50)->process($qb, $request);

        return $this->render('utilisateur/index.html.twig', array(
            'pagination' => $pagination,
        ));
    }

    /**
     * Creates a new utilisateur entity.
     *
     */
    public function newAction(Request $request)
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm('DalBundle\Form\UtilisateurType', $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($utilisateur);
            $em->flush();
            return $this->redirectToRoute('admin_auteur_index');
        }

        return $this->render('utilisateur/new.html.twig', array(
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing utilisateur entity.
     *
     */
    public function editAction(Request $request, Utilisateur $utilisateur)
    {
        $editForm = $this->createForm('DalBundle\Form\UtilisateurType', $utilisateur);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('admin_utilisateur_index');
        }

        return $this->render('utilisateur/edit.html.twig', array(
            'utilisateur' => $utilisateur,
            'edit_form' => $editForm->createView(),
        ));
    }


    public function ajaxAction(Request $request)
    {
        $qb = $this->getDoctrine()->getRepository("DalBundle:Utilisateur")->createQueryBuilder("u")
            ->select(' u.id, CONCAT(u.nom, \' \', u.prenom,\' - \', c.libelle) as value')
        ->leftJoin('u.classe','c');
        if ($request->get('query')) {
            $searchWords = explode(' ', $request->get('query'));
            $name = null;
            $kw = null;
            foreach ($searchWords AS $k => $search) {
                $qb->andWhere(
                    $qb->expr()->orX(
                        $qb->expr()->orX(
                            $qb->expr()->like('u.nom', ':search' . $k),
                            $qb->expr()->like('u.prenom', ':search' . $k),
                            $qb->expr()->like('c.libelle', ':search' . $k)
                        )
                    )
                );
                $qb->setParameter('search' . $k, '%' . $search . '%');
            }
        }

        $users = $qb->getQuery()->getResult();
        return new JsonResponse($users,200,array());

    }

}
