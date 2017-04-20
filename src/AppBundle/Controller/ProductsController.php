<?php

    namespace AppBundle\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    
    class ProductsController extends Controller {

        const PRODUCTS_TEST = [
            ['id' => 1, 'reference' => 'AFR-1'],
            ['id' => 2, 'reference' => 'AFR-2'],
            ['id' => 3, 'reference' => 'AFR-3'],
            ['id' => 4, 'reference' => 'AFR-4']
        ];

        /**
         * @Route("/products")
         * @Method("GET")
         */
        public function indexAction() {
            return $this->json(self::PRODUCTS_TEST);
        }

        /**
         * @Route("/products/{id}")
         * @Method("GET")
         */
        public function showAction($id) {
            foreach(self::PRODUCTS_TEST as $product) {
                if ($product['id'] === (int) $id) {
                    return $this->json($product);
                }
            }
            return $this->json(['error' => 'Product '.$id.' not found']);
        }

        /**
         * @Route("/products/{id}")
         * @Method({"PUT", "PATCH"})
         */
        public function editAction($id) {
            return new Response("editer le produit numéro ".$id);
        }

        /**
         * @Route("/products")
         * @Method("POST")
         */
        public function createAction() {
            return new Response("créer un nouveau produit");
        }

        /**
         * @Route("/products/{id}")
         * @Method("DELETE")
         */
        public function deleteAction($id) {
            return new Response("supprimer le produit numéro ".$id);
        }
    }