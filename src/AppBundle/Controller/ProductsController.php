<?php

    namespace AppBundle\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use AppBundle\Entity\Product;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\NumberType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;

    class ProductsController extends Controller {

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
                        // chercher toutes les catégories dans l'entity manager
                        $categories = $this->getDoctrine()
                            ->getRepository('AppBundle:Category') // on récupère le Repository Category
                            ->findAll(); // on récupère toutes les catégories
                        return $this->render('products/edit.html.twig', [
                            'categories' => $categories,
                            'form' => $this->createCreateOrEditForm($product)->createView()
                        ]);
                    }
                    else {
                        throw $this->createNotFoundException('No product found for id '.$id); // on lève une erreur 404
                    }
                case "PUT":
                case "PATCH":
                    // on récupère les données passées en POST
                    $reference = $request->request->get('reference');
                    $price = $request->request->get('price');
                    $category_id = $request->request->get('category_id');
                    
                    if ($product) {
                        $product->setReference($reference);
                        $product->setPrice($price);

                        if ($category_id) {
                            // on récupère la Catégorie via Doctrine
                            $categorie = $this->getDoctrine()->getRepository('AppBundle:Category')->find($category_id);
                            // on utilise le setter...
                            $product->setCategory($categorie);
                        }

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

                    // chercher toutes les catégories dans l'entity manager
                    $categories = $this->getDoctrine()
                        ->getRepository('AppBundle:Category') // on récupère le Repository Category
                        ->findAll(); // on récupère toutes les catégories

                    return $this->render('products/create.html.twig', [
                        'categories' => $categories,
                        'form' => $this->createCreateOrEditForm()->createView()
                    ]);

                case "POST":
                    // on récupère les données passées en POST
                    $reference = $request->request->get('reference');
                    $price = $request->request->get('price');
                    $category_id = $request->request->get('category_id');

                    $product = new Product();
                    $product->setReference($reference);
                    $product->setPrice($price);

                    if ($category_id) {
                        // on récupère la Catégorie via Doctrine
                        $categorie = $this->getDoctrine()->getRepository('AppBundle:Category')->find($category_id);
                        // on utilise le setter...
                        $product->setCategory($categorie);
                    }

                    $validator = $this->get('validator'); // j'appelle le container Validator
                    $errors = $validator->validate($product); // on recherche les erreurs
                    
                    if (count($errors) > 0) {
                        switch ($request->getRequestFormat()) {
                            case "json":
                                return $this->json('Product store failed: '. $errors);
                            case "html":
                                $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();
                                return $this->render('products/create.html.twig', compact('categories', 'errors'));
                        }
                    }

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

        private function createCreateOrEditForm(Product $product = null) {
            return $this
                ->createFormBuilder($product)
                ->add('reference', TextType::class)
                ->add('price', NumberType::class)
                ->add('category', EntityType::class, [
                    'class' => 'AppBundle:Category',
                    'choice_label' => 'designation',
                ])
                ->add('save', SubmitType::class, [
                    'label' => 'Save',
                    'attr' => [
                        'class' => 'btn btn-primary'
                        ]
                    ])
                ->getForm();
        }
    }