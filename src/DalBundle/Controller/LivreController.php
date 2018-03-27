<?php

namespace DalBundle\Controller;

use DalBundle\Entity\Keyword;
use DalBundle\Entity\Livre;
use DalBundle\Form\LivreType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Livre controller.
 *
 */
class LivreController extends Controller
{
    /**
     * Lists all livre entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->getRepository('DalBundle:Livre')
            ->createQueryBuilder('s')
            ->leftJoin('s.keywords', 'k')
            ->leftJoin('s.auteur', 'a');
        if ($request->get('search')) {
            $searchWords = explode(' ', $request->get('search'));
            $name = null;
            $kw = null;
            foreach ($searchWords AS $k => $search) {
                $qb->andWhere(
                    $qb->expr()->orX(
                        $qb->expr()->orX(
                            $qb->expr()->like('s.titre', ':search' . $k),
                            $qb->expr()->like('s.numero', ':search' . $k),
                            $qb->expr()->like('k.libelle', ':search' . $k),
                            $qb->expr()->like('a.nom', ':search' . $k),
                            $qb->expr()->like('a.prenom', ':search' . $k)
                        )
                    )
                );
                $qb->setParameter('search' . $k, '%' . $search . '%');
            }

            $qb->orderBy('s.numero', 'ASC');
        }

        $pagination = $this->get('pkshetlie.pagination')->setDefaults(50)->process($qb, $request);
        return $this->render('livre/index.html.twig', array(
            'pagination' => $pagination,
        ));
    }

    /**
     * Creates a new livre entity.
     *
     */
    public function newAction(Request $request)
    {
        $livre = new Livre();
        $livre->setNumero($this->getDoctrine()->getRepository("DalBundle:Livre")->getNumeroSuivant());
        $form = $this->createForm(LivreType::class, $livre);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($livre);
            $data = $request->get('dalbundle_livre')['keywords'];
            foreach ($data AS $data_uniq) {
                if (!is_numeric($data_uniq)) {
                    $k = $this->getDoctrine()->getRepository("DalBundle:Keyword")->findOneBy(['libelle' => $data_uniq]);
                    if ($k == null) {
                        $k = new Keyword();
                        $k->setLibelle($data_uniq);
                        $em->persist($k);
                        $em->flush();
                    }
                    $livre->addKeyword($k);
                }
            }
            $em->flush();
            if ($form->get('button.save_goto_new')->isClicked()) {
                return $this->redirectToRoute('admin_livre_new');
            } else {
                return $this->redirectToRoute('admin_livre_index');
            }
        }

        return $this->render('livre/new.html.twig', array(
            'livre' => $livre,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a livre entity.
     *
     */
    public function showAction(Livre $livre)
    {
        $deleteForm = $this->createDeleteForm($livre);

        return $this->render('livre/show.html.twig', array(
            'livre' => $livre,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to delete a livre entity.
     *
     * @param Livre $livre The livre entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Livre $livre)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_livre_delete', array('id' => $livre->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Displays a form to edit an existing livre entity.
     *
     */
    public function editAction(Request $request, Livre $livre)
    {
        $deleteForm = $this->createDeleteForm($livre);
        $form = $this->createForm('DalBundle\Form\LivreType', $livre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $data = $request->get('dalbundle_livre')['keywords'];
            foreach ($data AS $data_uniq) {
                if (!is_numeric($data_uniq)) {
                    $k = $this->getDoctrine()->getRepository("DalBundle:Keyword")->findOneBy(['libelle' => $data_uniq]);
                    if ($k == null) {
                        $k = new Keyword();
                        $k->setLibelle($data_uniq);
                        $em->persist($k);
                        $em->flush();
                    }
                    $livre->addKeyword($k);
                }
            }
            $em->flush();
            if ($form->get('button.save_goto_new')->isClicked()) {
                return $this->redirectToRoute('admin_livre_new');
            } else {
                return $this->redirectToRoute('admin_livre_index');
            }
        }

        return $this->render('livre/new.html.twig', array(
            'livre' => $livre,
            'form' => $form->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a livre entity.
     *
     */
    public function deleteAction(Request $request, Livre $livre)
    {
        $form = $this->createDeleteForm($livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($livre);
            $em->flush();
        }

        return $this->redirectToRoute('admin_livre_index');
    }

    public function countAction()
    {
        return new Response(count($this->getDoctrine()->getRepository('DalBundle:Livre')->findAll()));
    }

    public function ajaxAction(Request $request)
    {
        $qb = $this->getDoctrine()->getRepository("DalBundle:Livre")->createQueryBuilder("l")
            ->select(' l.id, CONCAT(l.numero, \' - \', l.titre) as value')
            ;
        if ($request->get('query')) {
            $searchWords = explode(' ', $request->get('query'));
            $name = null;
            $kw = null;
            foreach ($searchWords AS $k => $search) {
                $qb->andWhere(
                    $qb->expr()->orX(
                        $qb->expr()->orX(
                            $qb->expr()->like('l.titre', ':search' . $k),
                            $qb->expr()->like('l.numero', ':search' . $k)
                        )
                    )
                );
                $qb->setParameter('search' . $k, '%' . $search . '%');
            }
        }
//$qb->leftJoin('l.emprunts','e');
//        $qb->andWhere('(e.dateFin IS NULL AND e.dateDebut IS NULL) OR ()');
        $users = $qb->getQuery()->getResult();
        return new JsonResponse($users,200,array());

    }
}
