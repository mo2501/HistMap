<?php

namespace HistoryBundle\Repository;

/**
 * linkHighlightRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class linkHighlightRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAll(){

        /* @var linkRepository $repositoryM */
        $repositoryL = $this->getEntityManager()
                            ->getRepository('HistoryBundle:link');

        $highlights = parent::findBy(array(), array("id" => "ASC"));

        foreach($highlights as $key => $highlight){
            $highlights[$key]->links = $repositoryL->findByFrom($highlight->getPersonne());
        }

        return $highlights;
    }
}
