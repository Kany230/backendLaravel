<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Mail\EventRegistrationMail;
use Illuminate\Support\Facades\Mail;

class EventController extends Controller
{
    public function index()
    {
        // Voir tous les événements
        $events = Event::with('user')->get(); // Correct pour afficher tous les événements avec l'organisateur
        return response()->json($events);
    }

    public function notEvents()
    {
        // Voir les événements auxquels l'utilisateur n'est pas inscrit
        $user = auth()->user();
        $liste = $user->events()->pluck('event_id');
        $events = Event::whereNotIn('id', $liste)->get();

        return response()->json($events);
    }

    public function myEvents()
    {
        // Voir les événements auxquels l'utilisateur est inscrit
        $liste = auth()->user()->events; // Utilisation de la relation many-to-many avec participatedEvents()
        $events = Event::whereIn('id', $liste->pluck('id'))->get();

        return response()->json($events);
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
            'user_id' => auth()->id() // L'organisateur est l'utilisateur connecté
        ]);

        return response()->json(['message' => 'Evénement créé avec succès', 'event' => $event], 201);
    }

    public function register($id)
    {
        // Inscription à un événement
        $event = Event::findOrFail($id);
        $user = auth()->user();

        if ($event->users()->where('user_id', auth()->id())->exists()) {
            return response()->json(['message' => 'Vous êtes déjà inscrit'], 400);
        }

        $event->users()->attach(auth()->id());

        // Envoyer un mail à l'organisateur
        Mail::to($event->user->email)->send(new EventRegistrationMail($event, $user));

        return response()->json(['message' => 'Inscription réussie']);
    }

    public function cancel($id)
    {
        // Annuler l'inscription à un événement
        $event = Event::findOrFail($id);
        $event->users()->detach(auth()->id());

        return response()->json(['message' => 'Inscription annulée']);
    }
}
