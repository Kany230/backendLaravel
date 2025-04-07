<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre', // Modifie 'titre' pour qu'il corresponde à 'title' dans la base de données
        'description',
        'date',
        'lieu',
        'user_id'
    ];

   // 🔸 Organisateur de l'événement
   public function user()
   {
       return $this->belongsTo(User::class, 'user_id');
   }

   // 🔸 Participants inscrits à l'événement
   public function users()
   {
       return $this->belongsToMany(User::class);
   }
}
