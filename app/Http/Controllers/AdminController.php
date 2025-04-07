<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        // Voir tous les événements
        $events = Event::with('user')->get(); // Cette relation est correcte pour afficher l'organisateur
        return response()->json($events);
    }

    public function listUsers($id)
    {
        $event = Event::with('users')->findOrFail($id); // Utilisation de 'users' pour obtenir les participants
        return response()->json($event->users); // Cette méthode est correcte pour obtenir les utilisateurs inscrits
    }

    public function store(Request $request)
    {
        // Créer un événement
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'lieu' => 'required|string',
            'date' => 'required|date'
        ]);

        $event = Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'lieu' => $request->lieu,
            'user_id' => auth()->id() // L'organisateur est lié à l'événement
        ]);

        return response()->json(['message' => 'Evénement créé avec succès', 'event' => $event], 201);
    }

    public function show($id)
    {
        return Event::findOrFail($id); // Correct pour afficher un événement
    }

    public function update(Request $request, $id)
    {
        // Modifier un événement
        $event = Event::findOrFail($id);

        if (auth()->user()->role !== 'admin' && auth()->id() !== $event->user_id) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'lieu' => 'required|string',
            'date' => 'required|date'
        ]);

        $event->update([
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'lieu' => $request->lieu
        ]);

        return response()->json(['message' => 'Evénement mis à jour avec succès', 'event' => $event]);
    }

    public function destroy($id)
    {
        // Supprimer un événement
        $event = Event::findOrFail($id);

        if (auth()->user()->role !== 'admin' && auth()->id() !== $event->user_id) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $event->delete();

        return response()->json(['message' => 'Evénement supprimé avec succès']);
    }

    public function stats()
    {
        $eventCount = Event::count();
        $totalInscrit = DB::table('event_user')->count();

        $moisStat = DB::table('event_user')->select(DB::raw('COUNT(*) as count, MONTH(created_at) as month'))->groupBy('month')->get();

        return response()->json([
            'eventCount' => $eventCount,
            'totalInscrit' => $totalInscrit,
            'moisStat' => $moisStat
        ]);
    }

    public function generatePDF($id)
    {
        $event = Event::with('users')->findOrFail($id);

        if ($event->users->isEmpty()) {
            return response()->json(['message' => 'Aucun utilisateur inscrit à cet événement'], 404);
        }

        $pdf = PDF::loadView('pdf.listUsers', ['users' => $event->users]);

        return $pdf->download("listUsers.pdf");
    }
}
