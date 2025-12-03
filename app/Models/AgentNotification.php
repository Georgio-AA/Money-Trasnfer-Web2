<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentNotification extends Model
{
    protected $fillable = ['agent_id', 'type', 'message', 'read'];

    public function agent() {
        return $this->belongsTo(Agent::class);
    }
}
