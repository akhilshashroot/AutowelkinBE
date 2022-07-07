<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActivityDetails extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $datas;
    public $daily_activity;
    public $daily_activity_list;
    public $weekly_checklist;
    public $fullWeeklyChecklist;
    public $full_weekly_workreport;
    public $weekly_workreport;
    public $full_monthly_checklist;
    public $monthly_checklist;
    public $full_monthly_workreport_act;
    public $monthly_workreport_act;
    public $ticket_details;
    public function __construct($datas,$daily_activity,$daily_activity_list,$weekly_checklist,$fullWeeklyChecklist,$full_weekly_workreport,$weekly_workreport,
    $full_monthly_checklist,$monthly_checklist,$full_monthly_workreport_act,$monthly_workreport_act,$ticket_details)
    {
        $this->datas = $datas;
        $this->daily_activity = $daily_activity;
        $this->daily_activity_list = $daily_activity_list;
        $this->weekly_checklist = $weekly_checklist;
        $this->fullWeeklyChecklist = $fullWeeklyChecklist;
        $this->full_weekly_workreport = $full_weekly_workreport;
        $this->weekly_workreport = $weekly_workreport;
        $this->full_monthly_checklist = $full_monthly_checklist;
        $this->monthly_checklist = $monthly_checklist;
        $this->full_monthly_workreport_act = $full_monthly_workreport_act;
        $this->monthly_workreport_act = $monthly_workreport_act;
        $this->ticket_details = $ticket_details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->datas['email'], "HashRoot One")
        ->subject('Work Report - '.$this->datas['employee'])
        ->view('mail.activity_details', ['datas' => $this->datas,'daily_activity' => $this->daily_activity,'daily_activity_list'=>$this->daily_activity_list,'weekly_checklist' => $this->weekly_checklist,
    'fullWeeklyChecklist' => $this->fullWeeklyChecklist,'full_weekly_workreport' => $this->full_weekly_workreport,'weekly_workreport' => $this->weekly_workreport ,
    'full_monthly_checklist' => $this->full_monthly_checklist,'monthly_checklist' => $this->monthly_checklist,'full_monthly_workreport_act' => $this->full_monthly_workreport_act,
    'monthly_workreport_act' => $this->monthly_workreport_act, 'ticket_details' => $this->ticket_details]);
    }
}
