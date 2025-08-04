<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\EvolutionApiService;
use Illuminate\Http\Request;

class WhatsAppController extends Controller
{
    protected $evolutionApiService;

    public function __construct(EvolutionApiService $evolutionApiService)
    {
        $this->evolutionApiService = $evolutionApiService;
    }

    public function evolutionManager()
    {
        $config = $this->evolutionApiService->getConfig();
        
        return view('client.evolution-manager', compact('config'));
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