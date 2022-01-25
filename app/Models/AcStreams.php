<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Self_;

class AcStreams extends Model
{
    public function reviewer()
    {
        return $this->belongsTo('App\Models\AcAccount');
    }

    public function isInCurrentWeek(){
        if(date("W") == date("W", strtotime($this->start_date))){
            return true;
        }
       return false;
    }

    public function duration(){
        $t1 = strtotime( $this->start_date.' '.$this->end_time);
        $t2 = strtotime( $this->start_date.' '.$this->start_time);
        $diff = $t1 - $t2;
        $hours = $diff / ( 60 * 60 );
        return $hours;
    }

    public static function getNextStream(){
        $start_date_time = date("Y-m-d H:i:s");
        $start_date_time_with_buffer = timezone()->convertFromLocal(new Carbon(date('Y-m-d H:i:s', strtotime('+ '.config("ac.STREAM_TIME_BUFFER").' minutes', strtotime($start_date_time)))))->format('Y-m-d H:i:s');
        $model=AcStreams::where(\DB::raw("UNIX_TIMESTAMP(CONCAT(start_date,' ',start_time))"),'>=',strtotime($start_date_time_with_buffer))->where('status',0)->orderBy('start_date','asc')->first();
        return $model;
    }

    public static function getNextStreamId(){
       $model=self::getNextStream();
        if($model)
            return $model->id;
        return null;
    }
    public static function isNextSchedule($id){
        $model=self::getNextStream();
        if($model->id==$id)
            return true;
        return false;
    }

    public function getDateTimeDuration($minutes_to_add){

        $stream_id=$this->id;
        $stream=AcStreams::find($stream_id);
        $total_spent_mins=round(AcOrderItem::where('stream_id',$stream_id)->sum('quantity'));

        $time = strtotime($stream->start_date.' '.$stream->start_time.' +00');
        $endTime = date("Y-m-d H:i:s", strtotime('+'.$total_spent_mins.' minutes', $time));
        $duration = date("Y-m-d H:i:s", strtotime('+'.$minutes_to_add.' minutes', strtotime($endTime)));

        $start_date_time_tz = new \DateTime($endTime);

        $end_date_time_tz = new \DateTime($duration);

        return [
            'start'=>$start_date_time_tz->format('Y-m-d H:i:s'),
            'end'=>$end_date_time_tz->format('Y-m-d H:i:s')
        ];

    }

    public function getAvailableTimeFormatted(){
        $start_date_time = date('Y-m-d H:i:s',strtotime($this->start_date.' '.$this->start_time));
        $end_date_time = date('Y-m-d H:i:s',strtotime($this->start_date.' '.$this->end_time));

        return timezone()->convertToLocal(new Carbon($start_date_time),'l jS F h:i A').' - '.timezone()->convertToLocal(new Carbon($end_date_time),'h:i A (T)');
    }

    public function alottedTime($minutes_to_add){

        $stream_id=$this->id;
        $stream=AcStreams::find($stream_id);
        $total_spent_mins=round(AcOrderItem::where('stream_id',$stream_id)->sum('quantity'));

        $time = strtotime($stream->start_date.' '.$stream->start_time.' +00');
        $endTime = date("Y-m-d H:i:s", strtotime('+'.$total_spent_mins.' minutes', $time));
        $duration = date("Y-m-d H:i:s", strtotime('+'.$minutes_to_add.' minutes', strtotime($endTime)));

        $start_date_time_tz = new \DateTime($endTime);

        $start_date_time_tz->setTimezone(new \DateTimeZone(timezone()->getLocalTimezone()));

        $end_date_time_tz = new \DateTime($duration);
        $end_date_time_tz->setTimezone(new \DateTimeZone(timezone()->getLocalTimezone()));


        return $start_date_time_tz->format('h:i').' until '.$end_date_time_tz->format('h:i');
    }

    public function totalAvailableMinutes(){
        $to_time = strtotime($this->start_date.' '.$this->end_time);
        $from_time = strtotime($this->start_date.' '.$this->start_time);
        return round(abs($to_time - $from_time) / 60,2);
    }

    public function isAvailableSlot($minutes_to_add){
        $total_spent_mins=round(AcOrderItem::where('stream_id',$this->id)->sum('quantity'));
        $total=$total_spent_mins+round($minutes_to_add);
        if($total > $this->totalAvailableMinutes()){
            return false;
        }
        return true;
    }

    public function isSlotsFull(){
        $total_spent_mins=round(AcOrderItem::where('stream_id',$this->id)->sum('quantity'));
        if($total_spent_mins >= $this->totalAvailableMinutes()){
            return true;
        }
        return false;
    }
}
