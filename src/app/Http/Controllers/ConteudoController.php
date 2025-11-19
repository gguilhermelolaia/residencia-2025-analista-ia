<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conteudo;

class ConteudoController extends Controller
{
    // API: Salva o conteúdo gerado pela IA
    public function store(Request $request)
    {
        $conteudo = Conteudo::create($request->all());
        return response()->json($conteudo, 201);
    }

    // VISITANTE: Só vê o que está publicado
    public function index()
    {
        $conteudos = Conteudo::where('is_published', true)
                             ->orderBy('created_at', 'desc')
                             ->get();
        
        return view('welcome', ['conteudos' => $conteudos]);
    }

    // ADMIN: Vê tudo e gerencia
    public function admin()
    {
        $conteudos = Conteudo::orderBy('created_at', 'desc')->get();
        return view('admin', ['conteudos' => $conteudos]);
    }

    // ADMIN: Aprovar
    public function aprovar($id)
    {
        $conteudo = Conteudo::findOrFail($id);
        $conteudo->is_published = true;
        $conteudo->save();
        return redirect('/admin')->with('success', 'Conteúdo aprovado com sucesso!');
    }

    // ADMIN: Reprovar (Excluir)
    public function reprovar($id)
    {
        $conteudo = Conteudo::findOrFail($id);
        $conteudo->delete(); // Apaga do banco
        return redirect('/admin')->with('error', 'Conteúdo reprovado e removido.');
    }

    // ADMIN: Atualizar Texto (Edição)
    public function update(Request $request, $id)
    {
        $conteudo = Conteudo::findOrFail($id);
        $conteudo->conteudo = $request->input('conteudo_texto');
        $conteudo->save();
        return redirect('/admin')->with('info', 'Texto atualizado com sucesso!');
    }

    // NOVA FUNÇÃO: Cria um pedido vazio, esperando a IA preencher
    public function solicitar(Request $request)
    {
        $ticker = strtoupper($request->input('ticker'));

        // Cria um registro "em branco" com status especial
        Conteudo::create([
            'ticker' => $ticker,
            'conteudo' => '⏳ Aguardando processamento da IA...',
            'status' => 'Processando', // A IA vai procurar por esse status
            'is_published' => false
        ]);

        return redirect('/admin')->with('info', "Solicitação enviada para $ticker! Aguarde o Agente processar.");
    }

    public function apiIndex()
    {
        // Retorna tudo o que tem no banco em formato JSON
        return response()->json(Conteudo::all(), 200);
    }

    // ATUALIZAÇÃO VIA API (Para o Worker)
    public function updateApi(Request $request, $id)
    {
        $conteudo = Conteudo::findOrFail($id);
        
        // Atualiza apenas os campos que o Python mandou
        $conteudo->update([
            'conteudo' => $request->input('conteudo'),
            'status' => $request->input('status'),
            'is_published' => false // Continua precisando de aprovação humana
        ]);

        return response()->json($conteudo, 200);
    }

}