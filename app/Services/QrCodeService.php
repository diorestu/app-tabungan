<?php

namespace App\Services;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QrCodeService
{
    /**
     * Generate SVG Data URI string ready for <img src="..." />.
     */
    public static function svgDataUri(string $data): string
    {
        return (new QRCode)->render($data);
    }

    /**
     * Generate raw SVG string.
     */
    public static function rawSvg(string $data): string
    {
        $options = new QROptions([
            'imageBase64' => false,
        ]);

        return (new QRCode($options))->render($data);
    }
}
