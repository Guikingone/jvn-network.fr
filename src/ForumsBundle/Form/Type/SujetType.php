<?php

<<<<<<< HEAD
namespace ForumsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
=======
namespace ForumsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
>>>>>>> forums
use Symfony\Component\OptionsResolver\OptionsResolver;

class SujetType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('auteur')
<<<<<<< HEAD
            ->add('dateCreation', 'datetime')
            ->add('contenu')
            ->add('forum')
        ;
    }
    
=======
            ->add('contenu')
            ->add('save', SubmitType::class)
        ;
    }

>>>>>>> forums
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ForumsBundle\Entity\Sujet'
        ));
    }
}
