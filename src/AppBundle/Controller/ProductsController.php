<?php

    namespace AppBundle\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use AppBundle\Entity\Product;
    
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
                    // compact('products') = ['products' => $products]
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
            $product = $this->getDoctrine()
                ->getRepository('AppBundle:Product') // on récupère le Repository Product
                ->find($id); // on récupère le Produit ayant l'ID passé dans la route
            switch ($request->getRequestFormat()) { // on switche en fonction du _format passé dans la route
                case "json": // si c'est du json
                    if ($product) { // si un Produit a été trouvé dans la base de donnée
                        return $this->json($product); // afficher le produit au format JSON
                    }
                    else { // sinon si un Produit n'a pas été trouvé
                        return $this->json('Product '.$id.' not found', 404); // renvoyer une erreur au format JSON
                    }
                case "html": // si c'est du html
                    if ($product) { // si un Produit a été trouvé dans la base de donnée
                        return $this->render('products/show.html.twig', compact('product')); // afficher la vue Twig avec le produit trouvé dans la base de donnée
                    }
                    else { // sinon si un Produit n'a pas été trouvé
                        throw $this->createNotFoundException('No product found for id '.$id); // on lève une erreur 404
                    }
            }
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
            $product = $this->getDoctrine()
                ->getRepository('AppBundle:Product') // on récupère le Repository Product
                ->find($id); // on récupère le Produit ayant l'ID passé dans la route
            switch ($request->getMethod()) {
                case "GET":
                    if ($product) {
                        return $this->render('products/edit.html.twig', compact('product'));
                    }
                    else {
                        throw $this->createNotFoundException('No product found for id '.$id); // on lève une erreur 404
                    }
                case "PUT":
                case "PATCH":
                    // on récupère les données passées en POST
                    $reference = $request->request->get('reference');
                    $price = $request->request->get('price');
                    
                    if ($product) {
                        $product->setReference($reference);
                        $product->setPrice($price);

                        $em = $this->getDoctrine()->getManager();
                        $em->flush();
                    }

                    switch ($request->getRequestFormat()) {
                        case "json":
                            if ($product) {
                                return $this->json('Votre produit a bien ete edite');
                            }
                            else {
                                return $this->json('Product '.$id.' not found', 404); // renvoyer une erreur au format JSON
                            }
                        case "html":
                            if ($product) {
                                $this->addFlash('notice', 'Votre produit a bien été édité');
                                return $this->redirectToRoute('app_products_index');
                            }
                            else {
                                throw $this->createNotFoundException('No product found for id '.$id); // on lève une erreur 404
                            }
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
                    // on récupère les données passées en POST
                    $reference = $request->request->get('reference');
                    $price = $request->request->get('price');
                    
                    $product = new Product();
                    $product->setReference($reference);
                    $product->setPrice($price);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($product);
                    $em->flush();

                    switch ($request->getRequestFormat()) {
                        case "json":
                            return $this->json('Le produit '.$product->getId().' a bien été créé');
                        case "html":
                            $this->addFlash('notice', 'Le produit '.$product->getId().' a bien été créé');
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
            $product = $this->getDoctrine()
                ->getRepository('AppBundle:Product') // on récupère le Repository Product
                ->find($id); // on récupère le Produit ayant l'ID passé dans la route

            if ($product) {
                $em = $this->getDoctrine()->getManager(); // on récupère l'Entity Manager
                $em->remove($product); // on prépare la requête de suppression du Produit
                $em->flush(); // on éxecute la requête
            }

            switch ($request->getRequestFormat()) {
                case "json":
                    if ($product) { // Si le produit a été trouvé
                        return $this->json('Votre produit a bien ete supprime'); // on confirme la suppression
                    }
                    else { // sinon si un Produit n'a pas été trouvé
                        return $this->json('Product '.$id.' not found', 404); // renvoyer une erreur au format JSON
                    }
                case "html":
                    if ($product) {
                        $this->addFlash('notice', 'Votre produit a bien été supprimé');
                        return $this->redirectToRoute('app_products_index');
                    }
                    else {
                        throw $this->createNotFoundException('No product found for id '.$id); // on lève une erreur 404
                    }
            }
        }
    }