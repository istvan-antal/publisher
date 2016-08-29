<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use AppBundle\Transformer\ArrayToJSONStringTransformer;

class SiteType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name')
                ->add('siteTitle')
                ->add('webTrackingCode')
                ->add('deployType')
                ->add($builder->create('deploySettings', TextareaType::class)->addModelTransformer(new ArrayToJSONStringTransformer()))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Site'
        ));
    }

}
