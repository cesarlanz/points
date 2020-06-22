<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    protected $fillable = [
        'x',
        'y',
    ];

    private function calculate_distance($point) {
        return sqrt(pow($point->x - $this->x, 2) + pow($point->y - $this->y, 2));
    }

    private function compare($a, $b) {
        if ($a['distance'] == $b['distance']) {
            return 0;
        } elseif ($a['distance'] < $b['distance']) {
            return -1;
        } else {
            return 1;
        }
    }

    public function nearby($quantity = 0) {
        $points = Point::all();
        $distances = [];
        foreach ($points as $point) {
            if ($this->id !== $point->id) {
                array_push($distances, [
                    'point' => $point,
                    'distance' => $this->calculate_distance($point)
                ]);
            }
        }
        usort($distances, array($this, "compare"));
        return ($quantity > 0) ?array_slice($distances, 0, $quantity) : $distances;
    }
}
