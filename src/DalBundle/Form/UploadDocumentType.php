<?php

namespace DalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UploadDocumentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, array("label" => "Fichier"))
//            ->add('importBy', IntegerType::class, array("label" => "Importer par", "data" => 10))
            ->add('import', ChoiceType::class, array("label" => "Import",
                "choices_as_values" => true,
                "choices" => array(
                    "O.N.D.E" => "onde"
                )));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DalBundle\Entity\UploadDocument'
        ));
    }
}
