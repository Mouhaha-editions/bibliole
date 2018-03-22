<?php

namespace DalBundle\Form;

use Lifo\TypeaheadBundle\Form\Type\TypeaheadType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmpruntType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('utilisateur', TypeaheadType::class, [
                'class' => 'DalBundle\Entity\Utilisateur',
                'render' => "__toString",
                'minLength' => 3,
                'required' => true,
                'delay' => 100,
                'route' => 'user_resource',
                'attr' => ["placeholder" => "classe, nom, prenom", 'label' => ""]
            ])
            ->add('livre', TypeaheadType::class, [
                'class' => 'DalBundle\Entity\Livre',
                'render' => "__toString",
                'minLength' => 3,
                'required' => true,
                'delay' => 100,
                'route' => 'livre_resource',
                'attr' => ["placeholder" => "Titre, numéro", 'label' => ""]
            ])

        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DalBundle\Entity\Emprunt'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'dalbundle_emprunt';
    }


}
