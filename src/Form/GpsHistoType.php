<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\GpsHisto;

class GpsHistoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Pas besoin de rajouter les options avec ChoiceType vu que nous allons l'utiliser via API.
        // Le formulaire ne sera jamais affiché
        $builder
             ->add('id_android')
            ->add('ip_wan')
            ->add('ip_mac')

          ->add('nom_user')
          ->add('nom_machine')
         ->add('localisation')
         ->add('date_install')
         ->add('date_update')
        ->add('ip_lan')
        ->add('latitude_gps')
        ->add('longitude_gps')
        ->add('altitude_gps')
        ->add('accuaracy_gps')
        ->add('provider_gps')
        ->add('bearing_gps')
        ->add('speed_gps')
        ->add('elapsedrealtimeannos_gps')
        ->add('id_user')
        ->add('save', SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\GpsHisto',
            'csrf_protection' => false
        ]);
    }
}