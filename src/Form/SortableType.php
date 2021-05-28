<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class SortableType extends AbstractType
{
    public function getParent(): string
    {
        return CollectionType::class;
    }
}
