<?php

function dd($arr)
{
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
}

// like 2020-02-02 00:00:00
function midnight($timestamp = false)
{
    $timestamp = $timestamp ? $timestamp : time();
    return strtotime(date('Y-m-d', $timestamp) . ' midnight');
}

// like 2020-02-02 23:59:59
function endOfDay($timestamp = false)
{
    $timestamp = $timestamp ? $timestamp : time();
    return strtotime("tomorrow", $timestamp) - 1;
}

function escape($string)
{
    return addslashes(htmlspecialchars($string));
}

function generateCode($lenght = 6)
{
    $chars = array_merge(range('a', 'z'), range('A', 'Z'), range(0, 9));

    shuffle($chars);
    shuffle($chars);
    shuffle($chars);

    $code = '';
    for ($i=0; $i < $lenght; $i++) {
        $code .= $chars[array_rand($chars)];
    }

    return $code;
}

function hidePartOfString($value, $escapedChars = 1, $replacement = '*')
{
    $escapedChars = (int) $escapedChars;

    $valueParts = explode(' ', $value);
    if (!empty($valueParts)) {
        $value = '';
        foreach ($valueParts as $valuePart) {
            if ($escapedChars <= 0) {
                $valuePart = str_pad('', mb_strlen($valuePart), $replacement);
            } else {
                $hiddenSubString = str_pad('', mb_strlen($valuePart) - ($escapedChars * 2), $replacement);
                $valuePart = mb_substr($valuePart, 0, $escapedChars) . $hiddenSubString . mb_substr($valuePart, -$escapedChars);
            }
            $value .= (empty($value)) ? $valuePart : ' ' . $valuePart;
        }
    }

    return $value;
}

function showLastChars($value, $lenght, $replacement = '*')
{
    $value = str_repeat($replacement, strlen($value)-$lenght) . substr($value, -$lenght);
    return $value;
}

function getScheme()
{
    if (isset($_SERVER['HTTPS'])) {
        $scheme = $_SERVER['HTTPS'];
    } else {
        $scheme = '';
    }
    if (($scheme) && ($scheme != 'off')) {
        $scheme = 'https';
    } else {
        $scheme = 'http';
    }

    return $scheme;
}

function plural($n, $forms)
{
    return is_float($n)?$forms[1]:($n%10==1&&$n%100!=11?$forms[0]:($n%10>=2&&$n%10<=4&&($n%100<10||$n%100>=20)?$forms[1]:$forms[2]));
}
