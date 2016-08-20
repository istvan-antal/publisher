<?php

namespace AppBundle\Formatter;

use DateTime;

class TimeFormatter {
    
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    public function format($since = null, $to = null) {
        static $units = array(
            'y' => 'year',
            'm' => 'month',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second'
        );
        
        if ($to === null) {
            $to = new DateTime();
        }
        
        if (gettype($since) === 'integer') {
            $time1 = time();
            $time2 = $time1 + $since;

            $dt1 = new DateTime("@$time1");
            $dt2 = new DateTime("@$time2");
            
            $diff = $dt2->diff($dt1);
        } else {
            $diff = $to->diff($since);
        }
        
        foreach ($units as $attribute => $unit) {
            $count = $diff->$attribute;
            if (0 !== $count) {
                return $this->getDiffMessage($count, $diff->invert, $unit);
            }
        }
        
        return $since;
    }
    
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function getDiffMessage($count, $invert, $unit) {
        return $count.' '.$unit.($count>1?'s':'');
    }
}