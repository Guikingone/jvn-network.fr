<?php

namespace BlogBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ArticleEditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* On interdit de modifier la date de publication, cela assure la cohérence dans la BDD */
        $builder->remove('date');
    }

    public function getParent()
    {
      return new ArticleType();
    }
}
