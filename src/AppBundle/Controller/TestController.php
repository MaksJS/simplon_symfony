<?php

    namespace AppBundle\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;

    class TestController extends Controller {

        /**
         * @Route("/test/{username}", name="test", requirements={"username": "\D+"})
         */
        public function indexAction(Request $request, $username = "max") {
            //return new Response("<html><body><h1>coucou</h1></body></html>");
            return $this->render('test/index.html.twig', [
                'username' => $username
            ]);
        }

        /**
         * @Route("/test/{page}", name="test_show", requirements={"page": "\d+"})
         */
        public function showAction($page) {
            return new Response("ma page est ".$page);
        }
    }