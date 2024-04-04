<?php

namespace App\Doctrine;

use App\Entity\FortuneCookie;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class DiscontinuadoFilter extends SQLFilter
{

    /**
     * @param ClassMetadata $targetEntity
     * @param $targetTableAlias
     * @return string
     */
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        if($targetEntity->getReflectionClass()->name != FortuneCookie::class){
            return '';
        }

        return sprintf('%s.discontinued = %s', $targetTableAlias,$this->getParameter('discontinued'));
    }
}