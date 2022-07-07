<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <title></title>
    <style>
        table,
        td,
        div,
        h1,
        p {
            font-weight: 500;
            font-family: Arial, sans-serif;
        }
        .btn {margin: 10px 0px;
            border-radius: 4px;
            text-decoration: none;
            color: #fff !important;
            height: 46px;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: 600;
            background-image: linear-gradient(to right top, #021d68, #052579, #072d8b, #09369d, #093fb0) !important;
        }
        .btn:hover {
            text-decoration: none;
            opacity: .8;
        }
    </style>
</head>
<body style="margin:0;padding:0;">
    <table role="presentation"
        style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
        <tr>
            <td align="center" style="padding:0;">
                <table role="presentation"
                    style="width:600px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
                    <tr style="border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;">
                        <td align="left" style="padding:10px 25px;background:#fff; display: flex; align-items: center;">
                             <span style="font-weight: bold; padding-top: 10px;">Daily Checklist </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:36px 30px 42px 30px;">
                            <table role="presentation"
                                style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                                <tr>
                                    <td style="padding:0 0 36px 0;color:#153643;">
                                        {{"Activity"}}
                                    </td>
                                    <td style="padding:0 0 36px 0;color:#153643;">
                                        {{"Status"}}
                                    </td>
                                    <td style="padding:0 0 36px 0;color:#153643;">
                                        {{"Date"}}
                                    </td>
                                </tr>
                                @if(count($daily_activity))
                                @foreach($daily_activity as $data)
                                <tr>
                                    <td>
                                        {{$data['activity']}}
                                    </td>
                                    @if($data['status'] == 1)
                                    <td>
                                        {{'Done '}}
                                    </td> 
                                    <td>
                                        {{date('d-m-Y h:i a',$data['time'])}}
                                    </td>
                                    @else
                                    <td>{{'---'}}</td>
                                    <td> {{'---'}}</td>
                                    @endif
                                </tr>
                                @endforeach
                                @else
                                <tr colspan="3">{{'No Daily Checklists are assigned'}}</tr>
                                @endif
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        @if(count($daily_activity_list))
        <tr>
            <td align="center" style="padding:0;">
                <table role="presentation"
                    style="width:600px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
                    <tr style="border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;">
                        <td align="left" style="padding:10px 25px;background:#fff; display: flex; align-items: center;">
                             <span style="font-weight: bold; padding-top: 10px;"> Daily Report </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:36px 30px 42px 30px;">
                            <table role="presentation"
                                style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                                <tr>
                                    <td style="padding:0 0 36px 0;color:#153643;">
                                        {{"Activity"}}
                                    </td>
                                    <td style="padding:0 0 36px 0;color:#153643;">
                                        {{"Status"}}
                                    </td>
                                    <td style="padding:0 0 36px 0;color:#153643;">
                                        {{"Date"}}
                                    </td>
                                </tr>
                                @if(count($daily_activity_list))
                                @foreach($daily_activity_list as $data)
                                <tr>
                                    <td>
                                        {{$data['activity']}}
                                    </td>
                                    @if($data['status'] == 1)
                                    <td>
                                        {{'Done '}}
                                    </td> 
                                    <td>
                                    </td>
                                    @else
                                    <td>{{'---'}}</td>
                                    <td> {{'---'}}</td>
                                    @endif
                                </tr>
                                @endforeach
                                @endif
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        @endif
        <tr>
            <td align="center" style="padding:0;">
                <table role="presentation"
                    style="width:600px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
                    <tr style="border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;">
                        <td align="left" style="padding:10px 25px;background:#fff; display: flex; align-items: center;">
                             <span style="font-weight: bold; padding-top: 10px;"> Weekly Checklist </span>
                        </td>
                    </tr>
                    @if(count($weekly_checklist) || count($fullWeeklyChecklist))
                    <tr>
                        <td style="padding:36px 30px 42px 30px;">
                            <table role="presentation"
                                style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                                <tr>
                                    <td style="padding:0 0 36px 0;color:#153643;">
                                        {{"Task"}}
                                    </td>
                                    <td style="padding:0 0 36px 0;color:#153643;">
                                        {{"Time"}}
                                    </td>
                                    <td style="padding:0 0 36px 0;color:#153643;">
                                        {{"Status"}}
                                    </td>
                                </tr>
                                @if(count($weekly_checklist))
                                @foreach($weekly_checklist as $data)
                                <tr>
                                    <td>
                                        {{$data['wa_activity']}}
                                    </td>
                                    <td>
                                    {{date('d-m-Y',$data['wd_date'])}}
                                    </td>
                                    <td>
                                        {{'Done'}}
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                                @if(count($fullWeeklyChecklist))
                                @foreach($fullWeeklyChecklist as $data)
                                <tr>
                                    <td>
                                        {{$data['wa_activity']}}
                                    </td>
                                    <td>
                                    {{'---'}}
                                    </td>
                                    <td>
                                        {{'---'}}
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </table>
                        </td>
                    </tr>
                    @else
                    <tr colspan="3">{{'No weekly Checklists are assigned'}}</tr>
                    @endif
                </table>
            </td>
        </tr>
        <tr>
            <td align="center" style="padding:0;">
                <table role="presentation"
                    style="width:600px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
                    <tr style="border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;">
                        <td align="left" style="padding:10px 25px;background:#fff; display: flex; align-items: center;">
                             <span style="font-weight: bold; padding-top: 10px;"> Weekly Work Report </span>
                        </td>
                    </tr>
                    @if(count($weekly_workreport) || count($full_weekly_workreport))
                    <tr>
                        <td style="padding:36px 30px 42px 30px;">
                            <table role="presentation"
                                style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                                <tr>
                                    <td style="padding:0 0 36px 0;color:#153643;">
                                        {{"Task"}}
                                    </td>
                                    <td style="padding:0 0 36px 0;color:#153643;">
                                        {{"Time"}}
                                    </td>
                                    <td style="padding:0 0 36px 0;color:#153643;">
                                        {{"Report"}}
                                    </td>
                                </tr>
                                @if(count($weekly_workreport))
                                @foreach($weekly_workreport as $data)
                                <tr>
                                    <td>
                                        {{$data['wa_activity']}}
                                    </td>
                                    <td>
                                    {{date('d-m-Y',$data['wd_date'])}}
                                    </td>
                                    <td>
                                    {{$data['wd_status']}}
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                                @if(count($full_weekly_workreport))
                                @foreach($full_weekly_workreport as $data)
                                <tr>
                                    <td>
                                        {{$data['wa_activity']}}
                                    </td>
                                    <td>
                                    {{'---'}}
                                    </td>
                                    <td>
                                        {{'---'}}
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </table>
                        </td>
                    </tr>
                    @else
                    <tr colspan="3">{{'No weekly work reports are assigned'}}</tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>
</body>
</html>