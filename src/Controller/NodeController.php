<?php

namespace Varspool\DisqueAdmin\Controller;

use Disque\Connection\Node\Node;
use Symfony\Component\HttpFoundation\Request;

class NodeController extends BaseController
{
    public function indexAction(Request $request)
    {
        return $this->render('node/index.html.twig', [
            'prefix' => $request->query->get('prefix')
        ]);
    }

    public function showAction(string $id, ?string $prefix, Request $request)
    {
        $info = $this->disque->info();

        if ($prefix !== substr($id, 0, 8)) {
            return $this->redirect($this->url->generate('disque_admin_node_show', [
                'id' => $id,
                'prefix' => substr($id, 0, 8)
            ]), 302);
        }

        return $this->render('node/show.html.twig', [
            'prefix' => $prefix,
            'info' => $info,
            'id' => $id,
        ]);
    }

    /**
     * A simple table of HELLO details
     *
     * Shows ID, hostname, port and priority
     *
     * @param null|string $prefix
     * @param Request     $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function tableComponent(?string $prefix, Request $request)
    {
        $node = $this->disque->getConnectionManager()->getCurrentNode();

        /**
         * @var Node $node
         */
        $hello = $node->getHello();

        return $this->render('node/_table.html.twig', [
            'hello' => $hello,
            'prefix' => $prefix
        ]);
    }

    /**
     * A table of the INFO details for a given connection
     *
     * Note: we don't yet use a factory for connections, just a single connection per page. So, we need the prefix
     * to match the ID here, for the middleware to stay simple.
     *
     * @param string      $id
     * @param null|string $prefix
     * @param Request     $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function infoTableComponent(array $stats, Request $request)
    {
        return $this->render('node/_infoTable.html.twig', [
            'stats' => $stats,
        ]);
    }
}
