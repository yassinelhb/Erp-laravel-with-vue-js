<?php

namespace App\Http\Controllers;

use App\Mail\Bordereau;
use App\Ticket;
use Illuminate\Support\Facades\Mail;
use PDF;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(){
        $ticketspagination = Ticket::with('av','saison')->orderBy('id', 'desc')->paginate(10);
        return Response()->json(['ticketspagination' => $ticketspagination] );

    }

    public function show($search){
        $ticketspagination = Ticket::with('av','saison')
            ->whereHas('av', function ($q) use($search){
                $q->where('code',$search);})
            ->orWhere('nticket', 'like', '%'.$search.'%')->orderBy('id', 'desc')->paginate(10);
        return Response()->json(['ticketspagination' => $ticketspagination] );
    }

    public function filtersearch($filter,$search){
        $ticketspagination = Ticket::with('av','saison')
            ->whereHas('av', function ($q) use($filter){
                $q->where('code',$filter);})
            ->where('nticket', 'like', '%'.$search.'%')->orderBy('id', 'desc')->paginate(10);
        return Response()->json(['ticketspagination' => $ticketspagination] );
    }

    public function store(Request $request){
        $this->validate($request , [
            'caisse' => 'required|min:1|numeric',
            'dateP1' => 'required',
            'dateP2' => 'required',
            'poidsP1' => 'required|min:1|numeric',
            'poidsP2' => 'required|min:1|numeric',
            'peseur' => 'required',
            'vehicule' => 'required',
            'av' => 'required',
        ]);
        $ticket = new Ticket();
        $ticket->saison_id =  $request->saison;
        $ticket->caisse =  $request->caisse;
        $ticket->dateP1 =  $request->dateP1;
        $ticket->dateP2 =  $request->dateP2;
        $ticket->poidsP1 =  $request->poidsP1;
        $ticket->poidsP2 =  $request->poidsP2;
        $ticket->peseur =  $request->peseur;
        $ticket->vehicule =  $request->vehicule;
        $ticket->av_id =  $request->av;
        $ticket->observation = $request->observation;
        $ticket->save();
        $path = 'file/'.$ticket->id.'.pdf';
        PDF::loadView('page.ticketPDF',compact('ticket'))->save($path);
        $ticket->file = $path;
        $ticket->save();
        return Response()->json(['etat' => true ] );
    }
    public function update(Request $request,$id){

        $this->validate($request , [
            'caisse' => 'required|min:1|numeric',
            'dateP1' => 'required',
            'dateP2' => 'required',
            'poidsP1' => 'required|min:1|numeric',
            'poidsP2' => 'required|min:1|numeric',
            'peseur' => 'required',
            'vehicule' => 'required',
            'av' => 'required',
        ]);
        $ticket = Ticket::find($id);
        $ticket->saison_id =  $request->saison;
        $ticket->caisse =  $request->caisse;
        $ticket->dateP1 =  $request->dateP1;
        $ticket->dateP2 =  $request->dateP2;
        $ticket->poidsP1 =  $request->poidsP1;
        $ticket->poidsP2 =  $request->poidsP2;
        $ticket->peseur =  $request->peseur;
        $ticket->vehicule =  $request->vehicule;
        $ticket->av_id =  $request->av;
        $ticket->observation = $request->observation;
        $ticket->save();
        $path = 'file/'.$ticket->id.'.pdf';
        PDF::loadView('page.ticketPDF',compact('ticket'))->save($path);
        $ticket->file = $path;
        $ticket->save();

        return Response()->json(['etat' => true ] );
    }
    public function destroy($id){

        $ticket = Ticket::find($id);
        $ticket->delete();

        return Response()->json(['delete' => true]);

    }

}
