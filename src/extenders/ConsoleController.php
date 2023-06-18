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

    public static function progressBar($done, $total, $size = 50, $msg = null)
    {
        static $start_time;

        if ($done > $total) {
            return;
        }

        $start_time = $start_time ?: time();
        $now = time();
        $percent = (double)($done / $total);
        $bar = floor($percent * $size);

        $progressBar = "\e[1;32m\r[";
        $progressBar .= str_repeat("=", $bar);
        $progressBar .= ($bar < $size) ? ">" . str_repeat(" ", $size - $bar) : "=";
        $progressBar .= "] " . number_format($percent * 100, 0) . "% \e[0m";

        $rate = ($now - $start_time) / $done;
        $left = $total - $done;
        $eta = round($rate * $left, 2);
        $elapsed = $now - $start_time;

        $progressBar .= "$done / $total remaining: " . number_format($eta) . " sec.  elapsed: " . number_format($elapsed) . " sec. " . ($msg ?? "");

        echo "$progressBar ";

        flush();

        if ($done == $total) {
            echo PHP_EOL;
        }
    }

}