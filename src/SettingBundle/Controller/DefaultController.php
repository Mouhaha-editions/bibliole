<?php

namespace SettingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function funModeAction(Request $request)
    {
        $data = ['value' => $this->get('pkshetlie.settings')->get('fun_mode')];
        $form = $this->createFormBuilder($data)
            ->add('fun_mode', CheckboxType::class, ['label'=> "Mode fun ?"])
            ->getForm();

        $form = $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

        }




        return $this->render('SettingBundle:Default:fun_mode.html.twig', [
            'form' => $form
        ]);
    }
}
