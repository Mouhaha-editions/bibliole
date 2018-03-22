<?php

namespace DalBundle\Form;

use Lifo\TypeaheadBundle\Form\Type\TypeaheadType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class LivreType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero', TextType::class, [
                'label' => 'livre.label.numero',
                'label_attr' => ['class' => 'font-weight-bold'],
                'attr' => ["placeholder" => "livre.placeholder.numero"]
            ])->add('titre', TextType::class, [
                'required'=>true,
                'label' => 'livre.label.titre',
                'label_attr' => ['class' => 'font-weight-bold'],
                'attr' => ["placeholder" => "livre.placeholder.titre"]
            ])

            ->add('auteur', TypeaheadType::class, [
                'class' => 'DalBundle:Auteur',
                'required'=>true,
                'render' => "__toAutocomplete",
                'minLength' => 1,
                'delay' => 100,
                'route' => 'auteur_resource',
                'label' => 'livre.label.auteur',
                'label_attr' => ['class' => 'font-weight-bold'],
                'attr' => ["placeholder" => "livre.placeholder.auteur"],
            ])
            ->add('categorie', null, [
                'required'=>true,
                'label' => 'livre.label.categorie',
                'label_attr' => ['class' => 'font-weight-bold'],
                'attr' => ["placeholder" => "livre.placeholder.categorie"]
            ])
            ->add('keywords', Select2EntityType::class, [
                'multiple' => true,
                'remote_route' => 'keyword_resource',
                'class' => 'DalBundle\Entity\Keyword',
                'primary_key' => 'id',
                'text_property' => 'libelle',
                'page_limit' => 25,
                'allow_clear' => true,
                'allow_add' => [
                    'enabled' => true,
                    'new_tag_text' => ' (new)',
                    'new_tag_prefix' => '__',
                    'tags_separators' => '[","]',
                ],
//                'autostart'=>false,
                'placeholder' => 'livre.placeholder.keywords',
                'label' => 'livre.label.keywords',
                'label_attr' => ['class' => 'font-weight-bold'],
                'attr' => ["placeholder" => "livre.placeholder.keywords"]
            ])->add('resume', null, [
                'label' => 'livre.label.resume',
                'label_attr' => ['class' => 'font-weight-bold'],
                'attr' => ["placeholder" => "livre.placeholder.resume"]
            ])->add('isbn', null, [
                'label' => 'livre.label.isbn',
                'label_attr' => ['class' => 'font-weight-bold'],
                'attr' => ["placeholder" => "livre.placeholder.isbn"]
            ])->add('numeroTome', null, [
                'label' => 'livre.label.numeroTome',
                'label_attr' => ['class' => 'font-weight-bold'],
                'attr' => ["placeholder" => "livre.placeholder.numeroTome"]
            ])
            ->add('button.save_goto_new', SubmitType::class,['attr'=>['class'=>"btn bg-indigo pull-left"]])
            ->add('button.save_goto_list', SubmitType::class,['attr'=>['class'=>"btn bg-default pull-right"]])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DalBundle\Entity\Livre'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'dalbundle_livre';
    }


}
