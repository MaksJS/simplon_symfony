<?php

    namespace AppBundle\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use AppBundle\Entity\Product;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

    class ProductsController extends Controller {

        /**
         * @Route(
         *    "/products/list/{page}.{_format}", 
         *   defaults={"_format": "html", "page": "1"},
         * requirements={
         *      "_format": "html|json"
         *     }
         * )
         * @Method("GET")
         */
        public function indexAction(Request $request, $page) {
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('AppBundle:Product');
            switch ($request->getRequestFormat()) {
                case "json":
                    $products = $em
                        ->createQueryBuilder()
                        ->select('p.reference, p.price')
                        ->from(Product::class, 'p')
                        ->getQuery()
                        ->getResult();
                    return $this->json($products);
                case "html":
                    $page = min($repository->nbPages(), $page);
                    return $this->render('products/index.html.twig', array(
                        'products' => $repository->getByPage($page),
                        'pagesCount' => $repository->nbPages(),
                        'page' => $page,
                    ));
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
         * @Route("/products/edit/{id}")
         * @Method({"GET", "POST"})
         */
        public function editAction(Request $request, Product $product) {

            $form = $this->createForm('AppBundle\Form\ProductType', $product);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $em->flush();

                $this->addFlash('notice', $this->get('translator')
                    ->trans('edit_product_success', 
                        ['%id%' => $product->getId()]
                    )
                );
                return $this->redirectToRoute('app_products_index');
            }

            return $this->render('products/edit.html.twig', [
                'form' => $form->createView()
            ]);
        }

        /**
         * @Route("/products/api/{id}")
         * @Method({"PUT", "PATCH"})
         */
        public function apiEditAction(Request $request, int $id) {

            $product = $this->getDoctrine()->getRepository('AppBundle:Product')->find($id);
 
            if (!$product) { 
                return $this->json('Product not found', 404); 
            } 
            else {
                $reference = $request->request->get('reference'); 
                $price = $request->request->get('price'); 
                $category_id = $request->request->get('category_id');

                $product->setReference($reference);
                $product->setPrice($price);

                if ($category_id) { 
                    $categorie = $this->getDoctrine()->getRepository('AppBundle:Category')->find($category_id);
                    $product->setCategory($categorie); 
                }
                
                $validator = $this->get('validator'); 
                $errors = $validator->validate($product);

                if (count($errors) > 0) { 
                    return $this->json('Product edit ko cause:'. $errors, 400); 
                }
                else {
                    $em = $this->getDoctrine()->getManager(); 
                    $em->flush(); 
                    return $this->json('Product edit ok', 200); 
                }

            } 
        }

        /**
         * @Route("/products/create")
         * @Method({"GET", "POST"})
         */
        public function createAction(Request $request) {

            $product = new Product();
            $form = $this->createForm('AppBundle\Form\ProductType', $product);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $em->persist($product);
                $em->flush();

                $this->addFlash('notice', 'Le produit '.$product->getId().' a bien été créé');
                return $this->redirectToRoute('app_products_index');
            }   

            return $this->render('products/create.html.twig', [
                'form' => $form->createView()
            ]);
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
         * @Security("has_role('ROLE_ADMIN')")
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