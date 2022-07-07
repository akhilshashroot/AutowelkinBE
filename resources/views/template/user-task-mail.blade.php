 Hi {{$assignee}} , 
 <p>
     A new  {{$period_text}} task has been assigned by {{$task_creator}} on {{$date_created}}
     <h4>Task : </h4> {{$title}}
     <h4>Task in detail : </h4><p>{{$body}}</p>
     @if($date_text) <h4> Deadline : </h4><p>{{$date_text}}</p>
@endif
 </p>