<?php

namespace PruebaBundle\Repository;

use Doctrine\ORM\Query;

/**
 * FacturaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FacturaRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllArray() {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT f,s FROM PruebaBundle:Factura f join f.service s'
            )
            ->getArrayResult();
    }
    

}
