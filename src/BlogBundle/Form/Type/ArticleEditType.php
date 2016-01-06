<?php

namespace BlogBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use BlogBundle\Form\Type\ArticleType;

class ArticleEditType extends ArticleType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* On interdit de modifier la date de publication, cela assure la cohérence dans la BDD */
        $builder->remove('datePublication');
    }

    public function getParent()
    {
      return new ArticleType();
    }
}
