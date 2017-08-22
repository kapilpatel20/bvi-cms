<?php

namespace BviCmsBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CmsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CmsRepository extends EntityRepository
{
    public function getList($params = array()) {
        
        $query      = $this->createQueryBuilder('cm');
        $refineQtr  = $this->prepareFilters($query,$params);
        
        return $refineQtr;
        
    }
    
    //prepare filter operation
    
    public function prepareFilters($query,$params) {
        
        $sortOrd= 'desc'; $sortBy = 'cm.id';
        
        if(isset($params['title']) && $params['title']!='') {
            $query->where( $query->expr()->like('cm.title', ':TITLE'));
            $query->setParameter('TITLE','%'.$params['title'].'%');
        }
        if(isset($params['status']) && $params['status']!='') {
            $query->andWhere('cm.status=:STATUS');
            $query->setParameter('STATUS',$params['status']);
        }
        if(isset($params['sortBy']) && $params['sortBy']!='') {
            $sortBy = 'cm.'.$params['sortBy'];
        }
        if(isset($params['sortOrd']) && $params['sortOrd']!='') {
            $sortOrd = $params['sortOrd'];
        }
        
        $query->orderBy($sortBy, $sortOrd);
       # echo $query->getQuery()->getSql();die;
        return $query;
    }
}
