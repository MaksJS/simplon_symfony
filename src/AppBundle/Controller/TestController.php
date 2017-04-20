<?php

    namespace AppBundle\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Symfony\Component\HttpFoundation\Request;
    //use Symfony\Component\HttpFoundation\Response;

    class TestController extends Controller {

        /**
         * @Route("/test", name="test")
         */
        public function indexAction(Request $request) {
            //return new Response("<html><body><h1>coucou</h1></body></html>");
            return $this->render('test/index.html.twig', [
                'username' => 'max'
            ]);
        }
    }