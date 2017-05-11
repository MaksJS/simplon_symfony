<?php

namespace AppBundle\Controller;

use AppBundle\Entity\InvoiceLine;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Invoiceline controller.
 *
 * @Route("invoiceline")
 */
class InvoiceLineController extends Controller
{

    /**
     * Creates a new invoiceLine entity.
     *
     * @Route("/new", name="invoiceline_new")
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
        $invoiceLine = new Invoiceline();
        $form = $this->createForm('AppBundle\Form\InvoiceLineType', $invoiceLine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($invoiceLine);
            $em->flush();

            return $this->redirectToRoute('invoice_edit', array('id' => $invoiceLine->getInvoice()->getId()));
        }
    }

    /**
     * Displays a form to edit an existing invoiceLine entity.
     *
     * @Route("/{id}/edit", name="invoiceline_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, InvoiceLine $invoiceLine)
    {
        $editForm = $this->createForm('AppBundle\Form\InvoiceLineType', $invoiceLine);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('invoice_edit', array('id' => $invoiceLine->getInvoice()->getId()));
        }

        return $this->render('invoiceline/edit.html.twig', array(
            'invoiceLine' => $invoiceLine,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a invoiceLine entity.
     *
     * @Route("/{id}", name="invoiceline_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, InvoiceLine $invoiceLine)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($invoiceLine);
        $em->flush();

        return $this->redirectToRoute('invoice_edit', array('id' => $invoiceLine->getInvoice()->getId()));
    }
}
