<?php

namespace BorrowBundle\Controller;

use DalBundle\Entity\Emprunt;
use DalBundle\Form\EmpruntType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $emprunt = new Emprunt();
        $emprunt->setDateDebut(new \DateTime());
        $emprunt->setDateFinPrevue((new \DateTime())->modify('+15 days'));
        $form = $this->createForm(EmpruntType::class, $emprunt, ['attr' => ['class' => 'form-inline']])
            ->add('ok', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($emprunt);
            $em->flush();
            return $this->redirectToRoute('admin_emprunt_index');
        }


        $qb = $em->getRepository('DalBundle:Emprunt')->createQueryBuilder('e')
            ->leftJoin('e.utilisateur', 'u')
            ->leftJoin('u.classe', 'c')
            ->leftJoin('e.livre', 'l')
            ->orderBy('e.dateFinPrevue', 'ASC')
            ->where('e.dateFin IS NULL');


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
                            $qb->expr()->like('c.libelle', ':search' . $k),
                            $qb->expr()->like('l.titre', ':search' . $k)

                        )
                    )
                );
                $qb->setParameter('search' . $k, '%' . $search . '%');
            }
        }


        $pagination = $this->get('pkshetlie.pagination')->process($qb, $request);

        return $this->render('BorrowBundle:Default:index.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination

        ]);
    }

    public function retourAction(Request $request, Emprunt $emprunt)
    {
        $em = $this->getDoctrine()->getManager();
        $emprunt->setDateFin(new \DateTime());
        $em->flush();

        $this->addFlash('success',"Livre retourné ! ");
        return $this->redirectToRoute('admin_emprunt_index');
    }
}
