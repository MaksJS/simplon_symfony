<?php

    namespace AppBundle\Controller;


    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;

    class TestController {

        /**
         * @Route("/test", name="test")
         */
        public function indexAction(Request $request) {
            return new Response("coucou");
        }

    }