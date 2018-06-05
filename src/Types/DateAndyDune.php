<?php
/**
 * ----------------------------------------------
 * | Author: Andrey Ryzhov (Dune) <info@rznw.ru> |
 * | Site: www.rznw.ru                           |
 * | Phone: +7 (4912) 51-10-23                   |
 * | Date: 05.06.2018                            |
 * -----------------------------------------------
 *
 */


namespace AndyDune\DoctrineMongoOdmExperiments\Types;
use AndyDune\ConditionalExecution\ConditionHolder;
use AndyDune\DateTime\DateTime;
use Doctrine\ODM\MongoDB\Types\Type;
use MongoDB\BSON\UTCDateTime;

class DateAndyDune extends Type
{
    public function convertToPhpValue($value) : DateTime
    {
        if ($value instanceof UTCDateTime) {
            return new DateTime($value->toDateTime());
        }
        if ($value instanceof \MongoDate) {
            return new DateTime($value->toDateTime());
        }

        return new DateTime((int)$value);
    }

    public function closureToPHP(): string
    {
        // Return the string body of a PHP closure that will receive $value
        // and store the result of a conversion in a $return variable
        return '$return = (function($value) {
                if ($value instanceof \MongoDB\BSON\UTCDateTime) {
                    return new \AndyDune\DateTime\DateTime($value->toDateTime());
                }
                if ($value instanceof \MongoDate) {
                    return new \AndyDune\DateTime\DateTime($value->toDateTime());
                }
                
                return new \AndyDune\DateTime\DateTime((int)$value);
        })($value);';
    }


    public function convertToDatabaseValue($value) : UTCDateTime
    {
        if ($value instanceof DateTime) {
            return new UTCDateTime($value->getTimestamp() * 1000);
        }

        if ($value instanceof UTCDateTime) {
            return $value;
        }

        $conditionStringVariants = new ConditionHolder();
        $conditionStringVariants->add(function ($value) {
            $bool = preg_match('|^[+-]{1}|ui', $value);
            return $bool;
        });

        $conditionStringVariants->executeIfTrue(function ($value) {
            $dateTime = new DateTime();
            $dateTime->add($value);
            return new UTCDateTime($dateTime->getTimestamp() * 1000);
        });
        $conditionStringVariants->executeIfFalse(function ($value) {
            $value = strtotime($value);
            return new UTCDateTime($value * 1000);
        });

        $condition = new ConditionHolder();
        $condition->add(is_string($value))->executeIfTrue($conditionStringVariants)
            ->executeIfFalse(function ($value) {
                return new UTCDateTime($value * 1000);
            });

        return $condition->doIt($value);
    }
}