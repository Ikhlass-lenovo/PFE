<?php

// src/AppBundle/Form/RegistrationType.php

namespace App\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


use FOS\UserBundle\Form\Type\RegistrationFormType as BaseRegistrationFormType;
class RegistrationType extends AbstractType

{
   public function buildForm(FormBuilderInterface $builder, array $options)

   {
       $builder->add('nom');
       $builder->add('prenom');
       $builder->add('email');
       $builder->add('adresse');
       $builder->add('password');

   }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\User',
            'csrf_protection'   => false,
        ));
    }
   public function getParent()

   {
       return 'FOS\UserBundle\Form\Type\RegistrationFormType' :: class;
    }

    public function getBlockPrefix()
 
    {
        return 'app_user_registration';
    }
 
   /**public function getName()
 
    {
        return $this->getBlockPrefix();
    } 
 **/
 }