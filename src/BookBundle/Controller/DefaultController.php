<?php

namespace BookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        if($request->get('search_all',false)){
            return $this->render('BookBundle:Default:index.html.twig',[
                "resultats"=>$this->getDoctrine()->getRepository("DalBundle:Livre")->findByAnything($request->get('search_all',false))
            ]);

        }


        return $this->render('BookBundle:Default:index.html.twig',[]);
    }
}
