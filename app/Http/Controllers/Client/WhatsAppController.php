<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WhatsAppController extends Controller
{
    public function evolutionManager()
    {
        return view('client.evolution-manager');
    }

    public function disparoInteligente()
    {
        return view('client.disparo-inteligente');
    }

    public function leadHunter()
    {
        return view('client.lead-hunter');
    }

    public function simWarming()
    {
        return view('client.sim-warming');
    }

    public function gerenciarArquivos()
    {
        return view('client.gerenciar-arquivos');
    }
}