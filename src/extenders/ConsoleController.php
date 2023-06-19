<?php

namespace extenders;

use yii\console\Controller;

class ConsoleController extends Controller
{
    /**
     * @param $message
     * @return void
     */
    public static function message($message): void
    {
        echo $message . PHP_EOL;
    }
    /**
     * @param $message
     * @return void
     */
    public static function error($message): void
    {
        echo "\e[1;31m $message \e[0m\n";
    }
    /**
     * @param $message
     * @return void
     */
    public static function success($message): void
    {
        echo "\e[1;32m $message \e[0m\n";
    }
    /**
     * @param $message
     * @return void
     */
    public static function updated($message): void
    {
        echo "\e[1;35m $message \e[0m\n";
    }
    /**
     * @param $message
     * @return void
     */
    public static function warning($message): void
    {
        echo "\e[1;33m $message \e[0m\n";
    }

    public static function progressBar($done, $total, $size = 50, $message = null)
    {
        static $startTime;

        if ($done > $total) {
            return;
        }

        $startTime = $startTime ?: time();
        $now = time();
        $percent = ($done / $total);
        $barSize = floor($percent * $size);

        $progressBar = "\e[1;32m\r[";
        $progressBar .= str_repeat("=", $barSize);
        $progressBar .= ($barSize < $size) ? ">" . str_repeat(" ", $size - $barSize) : "=";
        $progressBar .= "] " . number_format($percent * 100, 0) . "% \e[0m";

        $rate = ($now - $startTime) / $done;
        $remaining = $total - $done;
        $estimatedTime = round($rate * $remaining, 2);
        $elapsedTime = $now - $startTime;

        $progressBar .= "$done / $total remaining: " . number_format($estimatedTime) . " sec. elapsed: " . number_format($elapsedTime) . " sec. " . ($message ?: "");

        echo "$progressBar ";

        flush();

        if ($done == $total) {
            echo PHP_EOL;
        }
    }

}