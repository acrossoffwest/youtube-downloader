<?php


namespace App;


class ProgressData
{
    public string $id;
    public int $percent;
    public string $status;

    public function __construct(string $id, int $percent, string $status)
    {
        $this->id = $id;
        $this->percent = $percent;
        $this->status = $status;
    }
}
