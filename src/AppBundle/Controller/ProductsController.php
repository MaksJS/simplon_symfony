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
         * @Route(
         *    "/products.{_format}", 
         *   defaults={"_format": "html"},
         *  requirements={
         *         "_format": "html|json"
         *     })
         * @Method("GET")
         */
        public function indexAction(Request $request) {
            $products = $this->getDoctrine()
                ->getRepository('AppBundle:Product')
                ->findAll();

            switch ($request->getRequestFormat()) {
                case "json":
                    return $this->json($products);
                case "html":
                    return $this->render('products/index.html.twig', compact('products'));
            }
        }

        /**
         * @Route(
         *   "/products/{id}.{_format}",
         *    defaults={"_format": "html"},
         *    requirements={
         *      "_format": "html|json",
         *      "id": "\d+"
         *     }
         * ) 
         * @Method("GET")
         */
        public function showAction(Request $request, int $id) {
            foreach(self::PRODUCTS_TEST as $product) { // je parcous tous les produits
                if ($product['id'] === $id) { // je cherche le produit ayant l'ID passé en paramètre
                    switch ($request->getRequestFormat()) {
                        case "json":
                            return $this->json($product);
                        case "html":
                            return $this->render('products/show.html.twig', compact('product'));
                            // compact('product') égal à [ 'product' => $product ]
                    }
                }   
            }
            // sinon je sors de la boucle, ça veut dire que j'ai pas trouvé le produit
            return $this->json(['error' => 'pas trouve']);
        }

        /**
         * @Route("/products/edit/{id}.{_format}",
         *    defaults={"_format": "html"},
         *    requirements={
         *      "_format": "html|json",
         *      "id": "\d+"
         *     }
         * ) 
         * @Method({"GET", "PUT", "PATCH"})
         */
        public function editAction(Request $request, int $id) {
            switch ($request->getMethod()) {
                case "GET":
                    foreach(self::PRODUCTS_TEST as $product) {
                        if ($product['id'] === $id) {
                            return $this->render('products/edit.html.twig', compact('product'));
                        }
                    }
                    return $this->json(['error' => 'Produit non existant']);
                case "PUT":
                case "PATCH":
                    switch ($request->getRequestFormat()) {
                        case "json":
                            return $this->json(['notice' => 'Votre produit a bien ete edite']);
                        case "html":
                            $this->addFlash('notice', 'Votre produit a bien été édité');
                            return $this->redirectToRoute('app_products_index');
                    }
            }
        }

        /**
         * @Route("/products/create.{_format}",
         *    defaults={"_format": "html"},
         *    requirements={
         *      "_format": "html|json",
         *      "id": "\d+"
         *     }
         * ) 
         * @Method({"GET", "POST"})
         */
        public function createAction(Request $request) {
            switch ($request->getMethod()) {
                case "GET":
                    return $this->render('products/create.html.twig');
                case "POST":
                    switch ($request->getRequestFormat()) {
                        case "json":
                            return $this->json(['notice' => 'Votre produit a bien ete cree']);
                        case "html":
                            $this->addFlash('notice', 'Votre produit a bien été créé');
                            return $this->redirectToRoute('app_products_index');
                    }
            }
        }

        /**
         * @Route("/products/{id}.{_format}",
         *    defaults={"_format": "html"},
         *    requirements={
         *      "_format": "html|json",
         *      "id": "\d+"
         *     }
         * ) 
         * @Method("DELETE")
         */
        public function deleteAction(Request $request, $id) {
            switch ($request->getRequestFormat()) {
                case "json":
                    return $this->json(['notice' => 'Votre produit a bien ete supprime']);
                case "html":
                    $this->addFlash('notice', 'Votre produit a bien été supprimé');
                    return $this->redirectToRoute('app_products_index');
            }
        }
    }