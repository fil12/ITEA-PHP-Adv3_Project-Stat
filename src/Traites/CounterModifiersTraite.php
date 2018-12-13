<?php
/**
 * Created by PhpStorm.
 * User: Саша
 * Date: 13.12.2018
 * Time: 15:49
 */

namespace App\Traites;

trait CounterModifiersTraite
{
    public function getCountModifiers(array $data): array
    {
        $count ['private'] = 0;
        $count ['protected'] = 0;
        $count ['public'] = 0;

        foreach ($data as $item) {
            $moddefier = \Reflection::getModifierNames($item->getModifiers());

            if ($moddefier[0] === 'private') {
                ++$count ['private'];
            } elseif ($moddefier[0] === 'protected') {
                ++$count ['protected'];
            } elseif ($moddefier[0] === 'public') {
                ++$count ['public'];
            }
        }

        return $count;
    }
}