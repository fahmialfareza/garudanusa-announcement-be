<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Event extends Model
{
    protected $fillable = ["event_name", "date", "desktop_photo", "mobile_photo", "header_footer_name", "selection_phase", "result_pass_text", "result_did_not_pass_text", "note"];
}