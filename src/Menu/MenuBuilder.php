<?php

namespace App\Menu;

use App\Entity\Category;
use Doctrine\ORM\EntityManager;
use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\RequestStack;

class MenuBuilder implements ContainerAwareInterface {

    use ContainerAwareTrait;

    private $em;
    private $factory;

    /**
     * @params [FactoryInterface $factory, EntityManager $entityManager]
     */
    public function __construct(FactoryInterface $factory, EntityManager $entityManager) {
        $this->factory = $factory;
        $this->em = $entityManager;
    }

    public function createMainMenu(RequestStack $requestStack) {
        $menu = $this->factory->createItem('root');

        $cats = $this->em->getRepository(Category::class)->findAll();
        foreach ($cats as $cat){
            $menu->addChild($cat->getName(), array('route' => ''));
        }

        return $menu;
    }

}
