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
     * Lists all invoiceLine entities.
     *
     * @Route("/", name="invoiceline_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $invoiceLines = $em->getRepository('AppBundle:InvoiceLine')->findAll();

        return $this->render('invoiceline/index.html.twig', array(
            'invoiceLines' => $invoiceLines,
        ));
    }

    /**
     * Creates a new invoiceLine entity.
     *
     * @Route("/new", name="invoiceline_new")
     * @Method({"GET", "POST"})
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

            return $this->redirectToRoute('invoiceline_show', array('id' => $invoiceLine->getId()));
        }

        return $this->render('invoiceline/new.html.twig', array(
            'invoiceLine' => $invoiceLine,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a invoiceLine entity.
     *
     * @Route("/{id}", name="invoiceline_show")
     * @Method("GET")
     */
    public function showAction(InvoiceLine $invoiceLine)
    {
        $deleteForm = $this->createDeleteForm($invoiceLine);

        return $this->render('invoiceline/show.html.twig', array(
            'invoiceLine' => $invoiceLine,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing invoiceLine entity.
     *
     * @Route("/{id}/edit", name="invoiceline_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, InvoiceLine $invoiceLine)
    {
        $deleteForm = $this->createDeleteForm($invoiceLine);
        $editForm = $this->createForm('AppBundle\Form\InvoiceLineType', $invoiceLine);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('invoiceline_edit', array('id' => $invoiceLine->getId()));
        }

        return $this->render('invoiceline/edit.html.twig', array(
            'invoiceLine' => $invoiceLine,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
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
        $form = $this->createDeleteForm($invoiceLine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($invoiceLine);
            $em->flush();
        }

        return $this->redirectToRoute('invoiceline_index');
    }

    /**
     * Creates a form to delete a invoiceLine entity.
     *
     * @param InvoiceLine $invoiceLine The invoiceLine entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(InvoiceLine $invoiceLine)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('invoiceline_delete', array('id' => $invoiceLine->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
