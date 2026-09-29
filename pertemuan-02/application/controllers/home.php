<?php
class Home extends Controller
{
    public function index(): void
    {
        $data = [
            'judul' => 'Fondasi MVC DPWL',
            'pesan' => 'Request telah melewati front controller, Router, Controller, dan View.'
        ];
        $this->view('home/index', $data);
    }

    public function info(string $topik = 'mvc'): void
    {
        $this->view('home/info', ['topik' => $topik]);
    }

    public function dosen(string $nid = '2522500013'): void
    {
        $data = [
            'title' => 'Detail dosen',
            'nid'   => $nid,
            'nama'  => 'Maulana Adit Pratama',
            'ruang' => 'dosen'
        ];
        $this->view('home/dosen', $data);
    }
}