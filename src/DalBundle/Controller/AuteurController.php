<?php

namespace DalBundle\Controller;

use DalBundle\Entity\Auteur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auteur controller.
 *
 */
class AuteurController extends Controller
{
    /**
     * Lists all auteur entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->getRepository('DalBundle:Auteur')->createQueryBuilder('a');
        if ($request->get('search')) {
            $searchWords = explode(' ', $request->get('search'));
            $name = null;
            $kw = null;
            foreach ($searchWords AS $k => $search) {
                $qb->andWhere(
                    $qb->expr()->orX(
                        $qb->expr()->orX(
                            $qb->expr()->like('a.nom', ':search' . $k),
                            $qb->expr()->like('a.prenom', ':search' . $k)

                        )
                    )
                );
                $qb->setParameter('search' . $k, '%' . $search . '%');
            }
        }
        $qb->orderBy('a.nom', 'ASC');

        $pagination = $this->get('pagination.core')->setDefaults(50)->process($qb, $request);

        return $this->render('auteur/index.html.twig', array(
            'pagination' => $pagination,
        ));
    }

    /**
     * Creates a new auteur entity.
     *
     */
    public
    function newAction(Request $request)
    {
        $auteur = new Auteur();
        $form = $this->createForm('DalBundle\Form\AuteurType', $auteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($auteur);
            $em->flush();

            return $this->redirectToRoute('admin_auteur_new', array('id' => $auteur->getId()));
        }

        return $this->render('auteur/new.html.twig', array(
            'auteur' => $auteur,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a auteur entity.
     *
     */
    public
    function showAction(Auteur $auteur)
    {
        $deleteForm = $this->createDeleteForm($auteur);

        return $this->render('auteur/show.html.twig', array(
            'auteur' => $auteur,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing auteur entity.
     *
     */
    public
    function editAction(Request $request, Auteur $auteur)
    {
        $deleteForm = $this->createDeleteForm($auteur);
        $editForm = $this->createForm('DalBundle\Form\AuteurType', $auteur);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_auteur_edit', array('id' => $auteur->getId()));
        }

        return $this->render('auteur/new.html.twig', array(
            'auteur' => $auteur,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a auteur entity.
     *
     */
    public
    function deleteAction(Request $request, Auteur $auteur)
    {
        $form = $this->createDeleteForm($auteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($auteur);
            $em->flush();
        }

        return $this->redirectToRoute('admin_auteur_index');
    }

    /**
     * Creates a form to delete a auteur entity.
     *
     * @param Auteur $auteur The auteur entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private
    function createDeleteForm(Auteur $auteur)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_auteur_delete', array('id' => $auteur->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    public
    function autocompleteAction(Request $request)
    {
        /** @var Auteur[] $entity */
        $entity = $this->getDoctrine()->getRepository("DalBundle:Auteur")->findByName($request->get('query'));
//        foreach ($entity AS $k => $e) {
//            foreach ($e AS $i => $v) {
//                $entity[$k][$i] = utf8_decode($v);
//            }
//        }
        return new JsonResponse($entity, 200, array('Content-Type' => 'application/json; charset=UTF-8'));
    }

    public
    function countAction()
    {
        return new Response(count($this->getDoctrine()->getRepository('DalBundle:Auteur')->findAll()));
    }
}
