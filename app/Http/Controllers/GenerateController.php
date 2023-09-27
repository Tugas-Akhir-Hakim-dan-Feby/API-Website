<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GenerateController extends Controller
{
    public function image($text)
    {
        $template = imagecreatefrompng(public_path('assets/images/blanko-background.png'));
        $whiteColor = imagecolorallocate($template, 0, 0, 0);
        $font = public_path('assets/fonts/Nunito-Bold.ttf');

        $text = explode(" ", $text);

        if (count($text) > 2) {
            $text[1] .= "\n";
        }

        $text = implode(' ', $text);

        imagettftext($template, 85, 0, 170, 450, $whiteColor, $font, $text);

        header("Content-type: image/png");
        imagepng($template);
    }
}
