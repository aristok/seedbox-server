<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Webserver extends Model
{
    protected $table = 'webservers';
    protected $fillable = ['status', 'fqdn', 'description'];

    const STATUSES = [
        'UP' =>'up',
        'DOWN' =>'down',
        'MAINTENANCE' =>'maintenance',
    ];

    public function statusUp()
    {
        $this->status = Webserver::STATUSES['UP'];
        $this->save();
    }

    public function statusDown()
    {
        $this->status = Webserver::STATUSES['DOWN'];
        $this->save();
    }
    public function statusMaintenance()
    {
        $this->status = Webserver::STATUSES['MAINTENANCE'];
        $this->save();
    }
}
