<?php

namespace App\Controller;

use App\Entity\Ticket;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tickets")
 */
class TicketsController extends AbstractController
{
    /**
     * @Route("/", name="tickets")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Ticket::class);
        $tickets = $repository->findBy(['done' => false]);

        return $this->render('tickets/index.html.twig', [
            'severityClass' => ['info', 'primary', 'success', 'warning', 'danger'],
            'tickets' => $tickets,
        ]);
    }

    /**
     * @Route("/archived", name="tickets_archived")
     */
    public function archived()
    {
        $repository = $this->getDoctrine()->getRepository(Ticket::class);
        $tickets = $repository->findBy(['done' => true]);

        return $this->render('tickets/index.html.twig', [
            'severityClass' => ['info', 'primary', 'success', 'warning', 'danger'],
            'tickets' => $tickets,
        ]);
    }

    /**
     * @Route("/add", name="add_ticket")
     */
    public function create() {
        return $this->render('tickets/add.html.twig');
    }

    /**
     * @Route("/save", name="save_ticket", methods={"POST"})
     */
    public function save(Request $request) {
        $title = $request->get('title');
        $description = $request->get('description');
        $severity = $request->get('severity');

        $ticket = new Ticket($title, $severity);
        $ticket->setDescription($description);

        $em = $this->getDoctrine()->getManager();
        $em->persist($ticket);
        $em->flush();

        return $this->redirectToRoute('tickets');
    }

    /**
     * @Route("/{id}", name="ticket_detail")
     */
    public function detail(int $id)
    {
        $repository = $this->getDoctrine()->getRepository(Ticket::class);
        $ticket = $repository->find($id);

        if (!$ticket) {
            throw new NotFoundHttpException('Ticket not found!');
        }

        return $this->render('tickets/detail.html.twig', [
            'severityClass' => ['info', 'primary', 'success', 'warning', 'danger'],
            'severityName' => ['INFO', 'LOW', 'NORMAL', 'HIGH', 'URGENT'],
            'ticket' => $ticket,
        ]);
    }

    /**
     * @Route("/{id}/done", name="ticket_mark_detail")
     */
    public function markDone(int $id)
    {
        $repository = $this->getDoctrine()->getRepository(Ticket::class);
        $ticket = $repository->find($id);

        if (!$ticket) {
            throw new NotFoundHttpException('Ticket not found!');
        }

        $ticket->setDone(true);

        $em = $this->getDoctrine()->getManager();
        $em->persist($ticket);
        $em->flush();

        return $this->redirectToRoute('tickets');
    }

    /**
     * @Route("/{id}/remove", name="ticket_remove")
     */
    public function remove(int $id)
    {
        $repository = $this->getDoctrine()->getRepository(Ticket::class);
        $ticket = $repository->find($id);

        if (!$ticket) {
            throw new NotFoundHttpException('Ticket not found!');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($ticket);
        $em->flush();

        return $this->redirectToRoute('tickets');
    }
}
