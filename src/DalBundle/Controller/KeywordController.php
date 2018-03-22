<?php

namespace DalBundle\Controller;

use DalBundle\Entity\Keyword;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Keyword controller.
 *
 */
class KeywordController extends Controller
{
    /**
     * Lists all keyword entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $keywords = $em->getRepository('DalBundle:Keyword')->findAll();

        return $this->render('keyword/index.html.twig', array(
            'keywords' => $keywords,
        ));
    }

    /**
     * Creates a new keyword entity.
     *
     */
    public function newAction(Request $request)
    {
        $keyword = new Keyword();
        $form = $this->createForm('DalBundle\Form\KeywordType', $keyword);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $em = $this->getDoctrine()->getManager();
            $em->persist($keyword);
            $em->flush();

            return $this->redirectToRoute('keyword_show', array('id' => $keyword->getId()));
        }

        return $this->render('keyword/new.html.twig', array(
            'keyword' => $keyword,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a keyword entity.
     *
     */
    public function showAction(Keyword $keyword)
    {
        $deleteForm = $this->createDeleteForm($keyword);

        return $this->render('keyword/show.html.twig', array(
            'keyword' => $keyword,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing keyword entity.
     *
     */
    public function editAction(Request $request, Keyword $keyword)
    {
        $deleteForm = $this->createDeleteForm($keyword);
        $editForm = $this->createForm('DalBundle\Form\KeywordType', $keyword);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('keyword_edit', array('id' => $keyword->getId()));
        }

        return $this->render('keyword/edit.html.twig', array(
            'keyword' => $keyword,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a keyword entity.
     *
     */
    public function deleteAction(Request $request, Keyword $keyword)
    {
        $form = $this->createDeleteForm($keyword);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($keyword);
            $em->flush();
        }

        return $this->redirectToRoute('keyword_index');
    }

    /**
     * Creates a form to delete a keyword entity.
     *
     * @param Keyword $keyword The keyword entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Keyword $keyword)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('keyword_delete', array('id' => $keyword->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    public function autocompleteAction(Request $request)
    {
        /** @var Keyword[] $entity */
        $entity = $this->getDoctrine()->getRepository("DalBundle:Keyword")->findByLibelle($request->get('q'));
        return new JsonResponse(['results'=>$entity], 200, array());
    }}
