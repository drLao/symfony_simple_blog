<?php

namespace App\Service;

class RandomTextGeneratorHelper
{
    public function generateRandomWords(int $numberOfWordsToGenerate): string
    {
        $simpleWordsArray = [
            ' posuit ', ' week ', ' insect ', ' ferrum ', ' aer ', ' dentium ', ' omnis ', ' sublust ', ' vitro ',
            ' mortuus ', ' caracterem ', ' dirige ', ' tunica ', ' perveniunt ', ' nasum ', ' risu ', ' pascuntur ',
            ' statera ', ' exercitium ', ' call ', ' adepto ', ' gavisus ', ' proeorous ', ' continent ',
            ' repentino ', ' vimus ', ' current ', ' postquam ', ' hauriret ', ' fuge ', ' latere ', ' tangerent ',
            ' fieri ', ' fera ', ' vitae ', ' sensi ', ' frigus ', ' figistrus ', ' castra ', ' aquam ', ' obtinuit ',
            ' festinate ', ' violet ', ' celebre ',
        ];

        $randomText = "";

        for ($i = 0; $i < $numberOfWordsToGenerate; $i++) {
            $randomText .= $simpleWordsArray[array_rand($simpleWordsArray)];
        }

        return $randomText;
    }
}